<?php

use PKEM\Model\Staff;

$staffDetails = "<table id='staff-details-table' class='key-value'>
  <tr><td>ឈ្មោះពេញ</td><td>{$details->name}</td></tr>
  <tr><td>ភេទ</td><td>{$details->sex}</td></tr>
</table>";

$section = <<<"SECTION"
<div id="staff-detail" class="task">
    <div class="title">ព័ត៌មានលម្អិត</div>
    <div class="content">
        $staffDetails
    </div>
</div>
SECTION;

$js = <<<"JS"
JS;

require __DIR__ . '/_base_/_base_.html.php';
