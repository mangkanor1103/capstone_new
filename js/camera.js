let stream = null;

function startCamera() {
    // ...existing camera code...
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        video.srcObject = null;
        video.style.display = 'none';
    }
}

// Add cleanup when modal closes
document.addEventListener('hidden.bs.modal', function () {
    stopCamera();
});