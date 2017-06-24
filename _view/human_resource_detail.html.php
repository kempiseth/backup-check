<?php

use PKEM\Model\Staff;

if ($details) {
    $id = Staff::formatId($details->id);
    $status = $details->is_active ? 'active' : 'caution';
    $status_text = $details->is_active ? 'សកម្ម' : 'ឈប់ថ្ងៃ '.$details->leave_date;
    $staffDetails = "<table staff_id='{$details->staff_id}' class='key-value list'>
  <tr><td>ឈ្មោះពេញ</td><td class='$status'>{$details->name}</td></tr>
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
  <tr><td>ប្រាក់ខែ</td><td class='caution'>{$details->salary} USD</td></tr>
</table>";
} else {
    $staffDetails = '';
    $status_text = '';
}

$section = <<<"SECTION"
<a class="back-button" href="/human-resource">ត្រលប់ក្រោយ</a>
<div id="staff-detail" class="task">
    <div class="title">ព័ត៌មានលម្អិត :: $id :: <span class="$status">$status_text</span> </div>
    <div class="content">
        $staffDetails
    </div>
</div>
SECTION;

$js = <<<"JS"
JS;

require __DIR__ . '/_base_/_base_.html.php';
