import cv2
import os
import numpy as np
from skimage.metrics import structural_similarity as ssim

# Load Haar Cascade Classifier for face detection
face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

# Function to compute similarity between two images
def compare_faces(image1, image2):
    # Resize images to the same size for comparison
    image1 = cv2.resize(image1, (200, 200))
    image2 = cv2.resize(image2, (200, 200))
    
    # Convert to grayscale
    image1_gray = cv2.cvtColor(image1, cv2.COLOR_BGR2GRAY)
    image2_gray = cv2.cvtColor(image2, cv2.COLOR_BGR2GRAY)
    
    # Compute the structural similarity index (SSIM) between the two images
    score, _ = ssim(image1_gray, image2_gray, full=True)
    return score

# Load known face images from the 'uploads' folder
known_faces = []
known_names = []

uploads_folder = 'uploads/'

for filename in os.listdir(uploads_folder):
    if filename.endswith(('.jpg', '.png')):
        image_path = os.path.join(uploads_folder, filename)
        image = cv2.imread(image_path)
        known_faces.append(image)
        known_names.append(filename)

# Capture video from webcam
cap = cv2.VideoCapture(0)

while True:
    ret, frame = cap.read()
    if not ret:
        break

    # Convert to grayscale for face detection
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    
    # Detect faces in the frame
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))
    
    for (x, y, w, h) in faces:
        # Draw rectangle around the face
        cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
        
        # Extract the detected face region
        detected_face = frame[y:y + h, x:x + w]

        # Compare the detected face to the known faces in the 'uploads' folder
        matched = False
        for known_face, name in zip(known_faces, known_names):
            similarity = compare_faces(detected_face, known_face)
            if similarity > 0.7:  # Threshold for face match
                cv2.putText(frame, f'Match Found: {name}', (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)
                matched = True
                break
        
        if not matched:
            cv2.putText(frame, 'No Match', (x, y - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 0, 255), 2)
    
    # Display the frame with face detection
    cv2.imshow('Webcam Feed', frame)
    
    # Break the loop if 'q' is pressed
    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
