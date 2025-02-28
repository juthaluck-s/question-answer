<script>
    document.getElementById('file-input').addEventListener('change', function() {
        let files = this.files;
        let maxImagesOnly = 20; // อัปโหลดรูปภาพได้สูงสุด 20 ไฟล์ (ถ้าเป็นภาพอย่างเดียว)
        let maxPDFsOnly = 10; // อัปโหลด PDF ได้สูงสุด 10 ไฟล์
        let maxTotal = 20; // รวมทั้งหมดไม่เกิน 20 ไฟล์
        let maxImagesWithPDF = 10; // จำกัดรูปภาพให้ใช้ได้แค่ 10 ไฟล์เมื่ออัปโหลดร่วมกับ PDF
        let label = this.nextElementSibling; // ดึง <label> ที่แสดงชื่อไฟล์

        let imageCount = 0; // จำนวนไฟล์รูปภาพทั้งหมด
        let pdfCount = 0; // จำนวนไฟล์ PDF

        // ตรวจสอบประเภทของไฟล์ที่อัปโหลด
        for (let i = 0; i < files.length; i++) {
            let ext = files[i].name.split('.').pop().toLowerCase(); // ดึงนามสกุลไฟล์
            if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                imageCount++;
            } else if (ext === 'pdf') {
                pdfCount++;
            }
        }

        let totalFiles = imageCount + pdfCount; // จำนวนไฟล์ทั้งหมด
        let errorMsg = "";

        // กรณีเลือกรูปภาพอย่างเดียว (ได้สูงสุด 20)
        if (pdfCount === 0 && imageCount > maxImagesOnly) {
            errorMsg = `คุณสามารถอัปโหลดรูปภาพได้สูงสุด 20 รูป`;
        } else if (pdfCount > 0 && imageCount > maxImagesWithPDF) {
            errorMsg =
                `เมื่อต้องการอัปโหลดรูปภาพและ PDF พร้อมกัน โดยสามารถเลือกไฟล์รูปภาพได้สูงสุด 10 รูป และไฟล์ PDF ได้สูงสุด 10 ไฟล์`;
        }

        // กรณีเลือก PDF เกิน 10 ไฟล์
        else if (pdfCount > maxPDFsOnly) {
            errorMsg = `คุณสามารถอัปโหลด PDF ได้สูงสุด 10 ไฟล์`;
        }
        // กรณีเลือกรูปภาพและ PDF รวมกัน และรูปภาพเกิน 10 ไฟล์



        // ถ้ามีข้อผิดพลาดให้แจ้งเตือนและรีเซ็ต
        if (errorMsg !== "") {
            Swal.fire({
                title: "อัปโหลดเกินจำนวนที่กำหนด!",
                text: errorMsg,
                icon: "warning",
                confirmButtonText: "ตกลง"
            });

            // รีเซ็ต input file
            this.value = "";

        } else {

        }
    });
</script>