<script>
let currentImages2 = []; // เก็บรูปของคอมเมนต์ที่เปิด
let currentIndex2 = 0;
const modalImage2 = document.getElementById("modalImage2");

// เปิด Modal และโหลดรูปทั้งหมดของคอมเมนต์
function openModal2(element, showAll = false) {
    closeModal2(event); // ปิด modal ส่วนที่หนึ่ง

    let modal = document.getElementById("imageModal2");
    modal.style.display = "flex"; // เปิด modal

    let parentDiv = element.closest('.comment-images');
    let imageList = JSON.parse(parentDiv.getAttribute('data-images'));
    let clickedIndex = showAll ? 0 : parseInt(element.dataset.index, 10);

    if (!imageList || imageList.length === 0) return;

    // กรองแค่ไฟล์รูปภาพ
    currentImages2 = imageList.filter(file => {
        const fileExt = file.split('.').pop().toLowerCase();
        return ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);
    }).map(file => "../assets/upload_file_comment/" + file);

    if (currentImages2.length === 0) return; // ถ้าไม่มีรูปภาพให้หยุดการทำงาน

    currentIndex2 = clickedIndex;

    // แสดงภาพใน modal2
    modalImage2.src = currentImages2[currentIndex2];
    resetZoom2();

    // สร้าง thumbnail ใหม่
    let thumbnailBar2 = document.getElementById("thumbnailBar2");
    thumbnailBar2.innerHTML = "";
    currentImages2.forEach((img, i) => {
        let thumb = document.createElement("img");
        thumb.src = img;
        thumb.classList.add("thumbnail");
        thumb.onclick = () => {
            modalImage2.src = img;
            currentIndex2 = i;
            resetZoom2();
        };
        thumbnailBar2.appendChild(thumb);
    });
}

// ปิด Modal
function closeModal2(event) {
    if (event.target === event.currentTarget || event.target.classList.contains('close-button')) {
        document.getElementById("imageModal2").style.display = "none";
    }
}

// เปลี่ยนรูปภาพ (ไปข้างหน้า / ย้อนกลับ)
function changeImage2(direction, event) {
    event.stopPropagation();
    currentIndex2 = (currentIndex2 + direction + currentImages2.length) % currentImages2.length;
    modalImage2.src = currentImages2[currentIndex2];
    resetZoom2();
}

// ใช้ปุ่มลูกศรเปลี่ยนรูป
document.addEventListener("keydown", (event) => {
    if (document.getElementById("imageModal2").style.display === "flex") {
        if (event.key === "ArrowRight") changeImage2(1, event);
        else if (event.key === "ArrowLeft") changeImage2(-1, event);
    }
});

// ตัวแปรเก็บสถานะการลาก
let isDragging2 = false;
let startDragX2 = 0,
    startDragY2 = 0;
let translateX2 = 0,
    translateY2 = 0;
let dragSpeed2 = 2; // ค่าคูณเพิ่มความเร็ว

// ตัวแปรสำหรับการขยาย
let scale2 = 1;
const zoomStep2 = 0.2;
const maxScale2 = 3;
const minScale2 = 1;

// รีเซ็ตการซูม
function resetZoom2() {
    modalImage2.style.transform = "translate(0, 0) scale(1)";
    translateX2 = 0;
    translateY2 = 0;
    scale2 = 1;
}

// เริ่มลากภาพ
function startDrag2(event) {
    event.preventDefault();
    isDragging2 = true;
    startDragX2 = event.type === "touchstart" ? event.touches[0].clientX : event.clientX;
    startDragY2 = event.type === "touchstart" ? event.touches[0].clientY : event.clientY;
}

// ฟังก์ชันลากภาพ
function onDrag2(event) {
    if (!isDragging2) return;

    let clientX2 = event.type === "touchmove" ? event.touches[0].clientX : event.clientX;
    let clientY2 = event.type === "touchmove" ? event.touches[0].clientY : event.clientY;

    // คูณค่าการลากด้วย dragSpeed เพื่อเพิ่มความเร็ว
    translateX2 += (clientX2 - startDragX2) * dragSpeed2;
    translateY2 += (clientY2 - startDragY2) * dragSpeed2;

    startDragX2 = clientX2;
    startDragY2 = clientY2;

    updateTransform2();
}

// ฟังก์ชันหยุดลาก
function stopDrag2() {
    isDragging2 = false;
}

// ฟังก์ชันอัปเดตการแสดงผล (ลาก + ซูม)
function updateTransform2() {
    modalImage2.style.transform = `translate(${translateX2}px, ${translateY2}px) scale(${scale2})`;
}

// ฟังก์ชันซูมภาพด้วย mouse wheel
function zoomWithScroll2(event) {
    event.preventDefault();
    let newScale = scale2 + (event.deltaY < 0 ? zoomStep2 : -zoomStep2);
    scale2 = Math.max(minScale2, Math.min(newScale, maxScale2));
    updateTransform2();
}

// ฟังก์ชัน double-click เพื่อรีเซ็ตขนาดรูปเป็น 1x
function resetZoomOnDoubleClick2(event) {
    event.preventDefault();
    resetZoom2(); // รีเซ็ตขนาดและตำแหน่งของรูป
}

// ใช้ mouse wheel เพื่อขยาย
modalImage2.addEventListener("wheel", zoomWithScroll2);

// ใช้ double-click เพื่อรีเซ็ตขนาด
modalImage2.addEventListener("dblclick", resetZoomOnDoubleClick2);

// ใช้ mouse & touch สำหรับลากรูป
modalImage2.addEventListener("mousedown", startDrag2);
modalImage2.addEventListener("touchstart", startDrag2);
document.addEventListener("mousemove", onDrag2);
document.addEventListener("touchmove", onDrag2);
document.addEventListener("mouseup", stopDrag2);
document.addEventListener("touchend", stopDrag2);
</script>