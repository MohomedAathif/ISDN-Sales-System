<?php

require_once "../config/database.php";
require_once "../dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$stmt = $conn->query("
SELECT orders.id, users.name, orders.total_amount, orders.created_at
FROM orders
JOIN users ON orders.user_id = users.id
ORDER BY created_at DESC
");

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = "<h2 style='text-align:center'>Sales Report</h2>";

$html .= "<table border='1' width='100%' cellpadding='8'>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Total</th>
<th>Date</th>
</tr>";

foreach($orders as $order){

$html .= "<tr>
<td>".$order['id']."</td>
<td>".$order['name']."</td>
<td>".$order['total_amount']."</td>
<td>".$order['created_at']."</td>
</tr>";

}

$html .= "</table>";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("sales_report.pdf");