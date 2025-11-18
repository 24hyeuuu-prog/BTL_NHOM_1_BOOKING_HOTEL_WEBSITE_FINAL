<?php
require_once 'config/config.php';

?>
<script>
// cuối file chitietdiemden.html
document.addEventListener('DOMContentLoaded', function() {
    loadDestinationRecommendations();
});

function loadDestinationRecommendations() {
    fetch('get_recommendations.php?limit=4')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.recommendations.length > 0) {
                updateRecommendationsSection(data.recommendations);
            }
        })
        .catch(error => console.error('Error loading recommendations:', error));
}

function updateRecommendationsSection(hotels) {
    const recommendationsGrid = document.querySelector('.recommendations-grid');
    if (!recommendationsGrid) return;
    
    recommendationsGrid.innerHTML = hotels.map(hotel => `
        <div class="recommendation-card">
            <div class="recommendation-image">
                <img src="${hotel.anhmain || '/placeholder.svg?height=150&width=200'}" alt="${hotel.Ten}">
            </div>
            <div class="recommendation-content">
                <h3 class="recommendation-title">${hotel.Ten}</h3>
                <p class="recommendation-description">
                    ${hotel.mota || 'Trải nghiệm những điều tuyệt vời nhất tại ' + hotel.khuvuc + ' với dịch vụ chất lượng cao.'}
                </p>
                <div class="recommendation-meta">
                    <div class="recommendation-rating">
                        ${generateStars(hotel.diemdg)} <span>${hotel.diemdg}</span>
                    </div>
                    <div class="recommendation-location">
                        <i class="fas fa-map-marker-alt"></i> ${hotel.khuvuc}
                    </div>
                </div>
                <button class="recommendation-btn" onclick="window.location.href='chitietkhachsan.php?id=${hotel.MaKS}'">
                    Xem chi tiết
                </button>
            </div>
        </div>
    `).join('');
}

function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    return '<i class="fas fa-star"></i>'.repeat(fullStars) +
           (hasHalfStar ? '<i class="fas fa-star-half-alt"></i>' : '') +
           '<i class="far fa-star"></i>'.repeat(emptyStars);
}
</script>

<?php
//  chitietdiemden.css
?>
<style>
/*chitietdiemden.css */
.recommendation-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 15px 0;
    font-size: 14px;
}

.recommendation-rating {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #FFD700;
}

.recommendation-rating span {
    color: #333;
    font-weight: 600;
    margin-left: 5px;
}

.recommendation-location {
    color: #666;
    display: flex;
    align-items: center;
    gap: 5px;
}

.recommendation-location i {
    color: #2196F3;
}

.recommendation-btn {
    background: #2196F3;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
    margin-top: 10px;
}

.recommendation-btn:hover {
    background: #1976D2;
}

@media (max-width: 768px) {
    .recommendation-meta {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
}
</style>

<?php
// PHP code để xử lý AJAX request cho recommendations
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['destination_recommendations'])) {
    header('Content-Type: application/json');
    
    $limit = intval($_GET['limit'] ?? 4);
    
    // Lấy khách sạn được đánh giá cao từ các khu vực khác nhau
    $sql = "SELECT DISTINCT k.* FROM khachsan k 
            WHERE k.diemdg >= 4.0 
            ORDER BY k.diemdg DESC, RAND() 
            LIMIT ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recommendations = array();
    while ($row = $result->fetch_assoc()) {
        // Lấy số lượng review
        $review_sql = "SELECT COUNT(*) as total_reviews FROM review WHERE MaKS = ?";
        $review_stmt = $conn->prepare($review_sql);
        $review_stmt->bind_param("i", $row['MaKS']);
        $review_stmt->execute();
        $review_result = $review_stmt->get_result();
        $review_count = $review_result->fetch_assoc()['total_reviews'];
        
        $row['total_reviews'] = $review_count;
        $recommendations[] = $row;
    }
    
    echo json_encode(['success' => true, 'recommendations' => $recommendations]);
    $stmt->close();
    $conn->close();
    exit;
}
?>