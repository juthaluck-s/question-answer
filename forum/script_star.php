<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".stars-container").forEach(container => {
        let id_detail = container.getAttribute("data-id-detail");
        let stars = container.querySelectorAll(".star");
        let result = document.getElementById(`result-${id_detail}`);

        function highlightStars(score) {
            stars.forEach(star => {
                star.classList.toggle("selected", star.dataset.score <= score);
            });
        }

        function fetchRating() {
            fetch(`get_rating.php?id_detail=${id_detail}&t=${Date.now()}`)
                .then(response => response.json())
                .then(data => {
                    let currentRating = parseInt(data.star_score) || 0; // ดึงค่า star_score ล่าสุด
                    highlightStars(currentRating); // ไฮไลต์ดาวตามคะแนนที่ได้รับ
                    if (data.dateScore) {
                        // result.textContent =
                        //     `Last rated on: ${new Date(data.dateScore).toLocaleString()}`;
                    } else {
                        // result.textContent = "No ratings yet.";
                    }
                });
        }

        stars.forEach(star => {
            star.addEventListener("click", function() {
                let score = this.dataset.score;
                fetch("save_rating.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `star=${score}&id_detail=${id_detail}`
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result === "updated" || result === "inserted") {
                            highlightStars(
                                score); // ไฮไลต์ดาวเมื่อมีการให้คะแนนใหม่
                            fetchRating(); // รีเฟรชการแสดงผลคะแนน
                        } else {
                            console.error("Error saving rating:", result);
                        }
                    });
            });
        });

        fetchRating(); // เรียกใช้ฟังก์ชันเพื่อดึงคะแนนล่าสุด
    });
});
</script>