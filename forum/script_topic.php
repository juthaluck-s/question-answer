<script>
// ตัวแปรเก็บสถานะการลาก
let isDragging = false;
let startDragX = 0,
    startDragY = 0;
let dragSpeed = 2; // ค่าคูณเพิ่มความเร็ว

// ตัวแปรสำหรับการขยาย
let scale = 1;
const zoomStep = 0.2;
const maxScale = 3;
const minScale = 1;

// ตัวแปรสำหรับการเลื่อนภาพ
let translateX = 0,
    translateY = 0;

// ตัวแปรสำหรับภาพ
let imagesTopic = <?= json_encode($image_list) ?>;
let currentImages = imagesTopic; // ใช้ imagesTopic เท่านั้น
let currentIndex = 0;
const modalImage1 = document.getElementById("modalImage1");

// ฟังก์ชันเปิด modal และแสดงภาพ
function openModal1(index) {
    // ตรวจสอบว่า index ที่ได้รับอยู่ในช่วงที่ถูกต้อง
    if (index < 0 || index >= currentImages.length) return;
    currentIndex = index;

    let modal = document.getElementById("imageModal1");
    let thumbnailBar1 = document.getElementById("thumbnailBar1");
    modal.style.display = "flex";
    modalImage1.src = currentImages[currentIndex];
    resetZoom();

    // สร้าง thumbnail สำหรับการเลือกภาพ
    thumbnailBar1.innerHTML = "";
    currentImages.forEach((img, i) => {
        let thumb = document.createElement("img");
        thumb.src = img;
        thumb.onclick = () => {
            modalImage1.src = img;
            currentIndex = i;
            resetZoom();
        };
        thumbnailBar1.appendChild(thumb);
    });
}

// ฟังก์ชันปิด modal
function closeModal1(event) {
    if (event.target === event.currentTarget || event.target.classList.contains('close-button')) {
        document.getElementById("imageModal1").style.display = "none";
    }
}

// ฟังก์ชันเปลี่ยนภาพ
function changeImage1(direction, event) {
    event.stopPropagation();
    currentIndex = (currentIndex + direction + currentImages.length) % currentImages.length;
    modalImage1.src = currentImages[currentIndex];
    resetZoom();
}

// ฟังก์ชันปรับการแสดงผล
function updateTransform() {
    modalImage1.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
}

// ฟังก์ชันขยายภาพ (zoom)
function zoomImage1(event) {
    event.preventDefault();
    if (event.deltaY < 0 && scale < maxScale) {
        scale += zoomStep; // ซูมเข้า
    } else if (event.deltaY > 0 && scale > minScale) {
        scale -= zoomStep; // ซูมออก
    }
    updateTransform();
}

// ฟังก์ชันรีเซ็ทการขยายภาพ
function resetZoom() {
    scale = 1;
    translateX = 0;
    translateY = 0;
    updateTransform();
}

// ฟังก์ชันเริ่มลาก
function startDrag(event) {
    event.preventDefault();
    isDragging = true;
    startDragX = event.type === "touchstart" ? event.touches[0].clientX : event.clientX;
    startDragY = event.type === "touchstart" ? event.touches[0].clientY : event.clientY;
}

// ฟังก์ชันลากภาพ
function onDrag(event) {
    if (!isDragging) return;

    let clientX = event.type === "touchmove" ? event.touches[0].clientX : event.clientX;
    let clientY = event.type === "touchmove" ? event.touches[0].clientY : event.clientY;

    translateX += (clientX - startDragX) * dragSpeed;
    translateY += (clientY - startDragY) * dragSpeed;

    startDragX = clientX;
    startDragY = clientY;

    updateTransform();
}

// ฟังก์ชันหยุดลาก
function stopDrag() {
    isDragging = false;
}

// การจัดการเหตุการณ์ keydown สำหรับการเปลี่ยนภาพ
document.addEventListener("keydown", (event) => {
    if (document.getElementById("imageModal1").style.display === "flex") {
        event.preventDefault();
        if (event.key === "ArrowRight") {
            changeImage1(1, event);
        } else if (event.key === "ArrowLeft") {
            changeImage1(-1, event);
        }
    }
});

// เพิ่มการจัดการ double-click สำหรับรีเซ็ทการขยายภาพ
modalImage1.addEventListener("dblclick", () => {
    resetZoom(); // รีเซ็ทการซูมและการเลื่อน
});

// เริ่มลากภาพ
modalImage1.addEventListener("mousedown", startDrag);
modalImage1.addEventListener("touchstart", startDrag);

// ลากภาพ
document.addEventListener("mousemove", onDrag);
document.addEventListener("touchmove", onDrag);

// หยุดลาก
document.addEventListener("mouseup", stopDrag);
document.addEventListener("touchend", stopDrag);

// ใช้ mouse wheel เพื่อขยาย
modalImage1.addEventListener("wheel", zoomImage1);

// เรียกใช้เมื่อมีการคลิกที่รูปภาพ
document.querySelectorAll('.clickable-image').forEach(img => {
    img.addEventListener('click', function() {
        // ตรวจสอบว่ารูปภาพนี้เป็นรูปในส่วนที่หนึ่งหรือไม่
        if (this.closest('.modal-section-1')) { // ตรวจสอบว่าเป็นรูปใน modal 1
            let index = parseInt(this.dataset.index, 10);
            openModal1(index);
        }
    });
});
</script>