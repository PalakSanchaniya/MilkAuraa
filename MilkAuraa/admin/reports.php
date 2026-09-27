<?php
include("../config/db.php");
include("../includes/auth.php");
include("../includes/header.php");

// Daily Report Query
$report = mysqli_query($conn, "
    SELECT date, 
           SUM(liters) as total_liters, 
           SUM(total_amount) as total_revenue
    FROM milk_entries
    GROUP BY date
    ORDER BY date DESC
");
?>

<style>
/* મુખ્ય કન્ટેનર */
.report-container {
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
}

.report-box {
    background: #ffffff !important; 
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    width: 100%;
}

.report-box h3 {
    color: #1e293b;
    font-size: 1.6rem;
    margin-bottom: 25px;
    font-weight: 700;
    border-left: 5px solid #667eea;
    padding-left: 15px;
}

/* સમરી કાર્ડ્સ ડિઝાઇન */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 35px;
}

.card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    margin: 0;
    font-size: 1.1rem;
    opacity: 0.9;
    border: none;
    padding: 0;
    color: white;
}

.card p {
    font-size: 2rem;
    font-weight: 800;
    margin: 10px 0 0 0;
}

/* ટેબલ ડિઝાઇન - Full Width */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

thead th {
    background: #f1f5f9;
    color: #1e293b;
    text-align: left;
    padding: 15px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.85rem;
    border-bottom: 2px solid #e2e8f0;
}

tbody td {
    padding: 15px;
    border-bottom: 1px solid #f1f5f9;
    color: #000000 !important; /* એકદમ કાળા અક્ષરો */
    font-size: 1rem;
    font-weight: 500;
}

tbody tr:hover {
    background-color: #f8fafc;
}

.revenue-text {
    font-weight: 700;
    color: #059669; /* ગ્રીન કલર પૈસા માટે */
}
</style>

<div class="report-container">
    <div class="report-box">
        <h3>Business Reports</h3>

        <div class="cards">
            <?php
            $total = mysqli_query($conn, "
                SELECT 
                    SUM(liters) as total_liters,
                    SUM(total_amount) as total_revenue
                FROM milk_entries
            ");
            $t = mysqli_fetch_assoc($total);
            ?>

            <div class="card">
                <h3>Overall Total Milk</h3>
                <p><?php echo number_format($t['total_liters'] ?? 0, 2); ?> <span style="font-size: 1rem;">Ltrs</span></p>
            </div>

            <div class="card">
                <h3>Overall Total Revenue</h3>
                <p>₹ <?php echo number_format($t['total_revenue'] ?? 0, 2); ?></p>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Total Liters</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1; ?>
                    <?php while($row = mysqli_fetch_assoc($report)) { ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td style="color: #6b46c1; font-weight: 600;">
                            <?php echo date('d-M-Y', strtotime($row['date'])); ?>
                        </td>
                        <td><?php echo number_format($row['total_liters'], 2); ?> L</td>
                        <td class="revenue-text">₹ <?php echo number_format($row['total_revenue'], 2); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>