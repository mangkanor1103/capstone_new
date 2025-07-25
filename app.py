from flask import Flask, Response, jsonify, send_file
from flask_cors import CORS
import cv2
import mediapipe as mp
import os

os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'  # Suppress TensorFlow INFO and WARNING messages

app = Flask(__name__)
CORS(app)

# Initialize MediaPipe Face Mesh
mp_face_mesh = mp.solutions.face_mesh
mp_drawing = mp.solutions.drawing_utils
mp_drawing_styles = mp.solutions.drawing_styles

face_mesh = mp_face_mesh.FaceMesh(
    max_num_faces=1,
    refine_landmarks=True,
    min_detection_confidence=0.5,
    min_tracking_confidence=0.5
)

# Movement tracking variables
prev_x = None
movement_direction = "CENTER"
gaze_direction = "CENTER"
mouth_status = "CLOSED"
warning_image_path = "warnings/latest_warning.jpeg"  # Fixed file name for single warning image

# Ensure the warnings folder exists
warnings_folder = "warnings"
if not os.path.exists(warnings_folder):
    os.makedirs(warnings_folder)


def calculate_gaze_direction(face_landmarks, image_shape):
    # Get specific landmarks for left and right eye
    left_eye_left = face_landmarks.landmark[33]
    left_eye_right = face_landmarks.landmark[133]
    right_eye_left = face_landmarks.landmark[362]
    right_eye_right = face_landmarks.landmark[263]
    nose_tip = face_landmarks.landmark[1]

    height, width = image_shape[:2]
    nose_x = int(nose_tip.x * width)
    left_eye_center_x = int((left_eye_left.x + left_eye_right.x) / 2 * width)
    right_eye_center_x = int((right_eye_left.x + right_eye_right.x) / 2 * width)

    eye_line_center_x = (left_eye_center_x + right_eye_center_x) // 2
    horizontal_diff = nose_x - eye_line_center_x

    threshold = 20

    if horizontal_diff > threshold:
        return "RIGHT"
    elif horizontal_diff < -threshold:
        return "LEFT"
    return "CENTER"


def check_mouth_open(face_landmarks, image_shape, threshold=15):
    height, width = image_shape[:2]
    upper_lip = face_landmarks.landmark[13]
    lower_lip = face_landmarks.landmark[14]

    upper_lip_y = int(upper_lip.y * height)
    lower_lip_y = int(lower_lip.y * height)

    lip_distance = abs(lower_lip_y - upper_lip_y)
    return "OPEN" if lip_distance > threshold else "CLOSED"


def save_warning_image(frame):
    """Save only one warning image by overwriting the existing one."""
    # Delete the previous warning image if it exists
    if os.path.exists(warning_image_path):
        os.remove(warning_image_path)

    # Save the current warning image
    cv2.imwrite(warning_image_path, frame)
    print(f"Warning image saved: {warning_image_path}")


def generate_frames():
    global prev_x, movement_direction, gaze_direction, mouth_status
    cap = cv2.VideoCapture(0)
    frame_width = int(cap.get(3))
    center_x = frame_width // 2

    while True:
        success, frame = cap.read()
        if not success:
            break

        frame = cv2.flip(frame, 1)

        frame_rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

        results = face_mesh.process(frame_rgb)

        if results.multi_face_landmarks:
            for face_landmarks in results.multi_face_landmarks:
                # Draw the face mesh
                mp_drawing.draw_landmarks(
                    image=frame,
                    landmark_list=face_landmarks,
                    connections=mp_face_mesh.FACEMESH_TESSELATION,
                    landmark_drawing_spec=None,
                    connection_drawing_spec=mp_drawing_styles.get_default_face_mesh_tesselation_style()
                )

                # Calculate face position
                nose_tip = face_landmarks.landmark[1]
                x = int(nose_tip.x * frame.shape[1])

                if prev_x is not None:
                    if x < center_x - 100:
                        movement_direction = "LEFT"
                    elif x > center_x + 100:
                        movement_direction = "RIGHT"
                    else:
                        movement_direction = "CENTER"

                prev_x = x

                # Calculate gaze direction
                gaze_direction = calculate_gaze_direction(face_landmarks, frame.shape)

                # Check mouth status
                mouth_status = check_mouth_open(face_landmarks, frame.shape)

                # If a warning condition is met, save the frame
                if movement_direction != "CENTER" or gaze_direction != "CENTER" or mouth_status == "OPEN":
                    save_warning_image(frame)

        ret, buffer = cv2.imencode('.jpg', frame)
        frame = buffer.tobytes()

        yield (b'--frame\r\n'
               b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n')


@app.route('/video_feed')
def video_feed():
    return Response(generate_frames(),
                    mimetype='multipart/x-mixed-replace; boundary=frame')


@app.route('/face_position')
def face_position():
    global movement_direction, gaze_direction, mouth_status
    response_gaze = gaze_direction if movement_direction == "CENTER" else "CENTER"
    return jsonify({
        "position": movement_direction,
        "gaze": response_gaze,
        "mouth": mouth_status
    })


@app.route('/get_warning_image')
def get_warning_image():
    """Serve the latest warning image to the client."""
    if os.path.exists(warning_image_path):
        return send_file(warning_image_path, mimetype='image/jpeg')
    return jsonify({"error": "No warning image available"}), 404


@app.route('/restart', methods=['POST'])
def restart_session():
    """Reset all tracking variables to their initial state."""
    global prev_x, movement_direction, gaze_direction, mouth_status
    
    # Reset all tracking variables
    prev_x = None
    movement_direction = "CENTER"
    gaze_direction = "CENTER" 
    mouth_status = "CLOSED"
    
    # Clear any saved warning image
    if os.path.exists(warning_image_path):
        os.remove(warning_image_path)
        
    return jsonify({"status": "success", "message": "Session restarted successfully"})


if __name__ == '__main__':
    try:
        app.run(host='127.0.0.1', port=5000, debug=False, threaded=True)
    except Exception as e:
        print(f"Error: {e}")
    finally:
        cv2.destroyAllWindows()