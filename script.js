document.addEventListener("DOMContentLoaded", function () {
    Papa.parse("data.csv", {
        download: true,
        header: true,
        complete: function (results) {
            displayShops(results.data);
        }
    });
});

function displayShops(shops) {
    const container = document.getElementById("shop-container");
    shops.forEach(shop => {
        const card = document.createElement("div");
        card.className = "card";

        const img = document.createElement("img");
        img.src = shop.Image_URL ? shop.Image_URL : "photo.jpg"; // ใช้ fallback image

        const content = document.createElement("div");
        content.className = "card-content";
        content.innerHTML = `
            <h3>${shop.Name}</h3>
            <p>${shop.Address}</p>
            <p>โทร: ${shop.Phone}</p>
            <p>คะแนน: ${shop.Rating}</p>
            <p>${shop.Description || "ไม่มีข้อมูล"}</p>
            <a href="${shop.Website || "#"}" target="_blank">เว็บไซต์</a><br>
            <a href="${shop.Listing_URL || "#"}" target="_blank">ดูใน Google Maps</a>
        `;

        card.appendChild(img);
        card.appendChild(content);
        container.appendChild(card);
    });
}
