<?php

use PKEM\Model\Staff;

$staffDetails = "<table staff_id='{$details->staff_id}' class='key-value'>
  <tr><td>ឈ្មោះពេញ</td><td>{$details->name}</td></tr>
  <tr><td>ភេទ</td><td>{$details->sex}</td></tr>
  <tr><td>ថ្ងៃខែឆ្នាំកំណើត</td><td>{$details->dob}</td></tr>
  <tr><td>ទូរស័ព្ទ</td><td>{$details->phone}</td></tr>
  <tr><td>អាសយដ្ឋាន</td><td>{$details->address}</td></tr>
  <tr><td>កម្រិតការអប់រំ</td><td>{$details->education}</td></tr>
  <tr><td>ជំនាញ</td><td>{$details->skill}</td></tr>
  <tr><td>ភាសា</td><td>{$details->language}</td></tr>
  <tr><td>មុខតំណែង</td><td>{$details->position}</td></tr>
  <tr><td>នាយកដ្ឋាន</td><td>{$details->department}</td></tr>
  <tr><td>ថ្ងៃចូលធ្វើការ</td><td>{$details->enroll_date}</td></tr>
  <tr><td>ប្រាក់ខែ</td><td>{$details->salary} USD</td></tr>
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
