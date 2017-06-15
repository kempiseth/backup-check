<?php

use PKEM\Model\Staff;

$staffsRows = '';
foreach ($staffs as $staff) {
    $deleteIcon = $_SESSION['user']->canDelete() ?
        "<img class='icon' action='delete' src='/static/image/delete.jpg' title='Remove'>" : '';
    $updateIcon = $_SESSION['user']->canUpdate() ?
        "<img class='icon' action='update' src='/static/image/update.jpg' title='Edit'>" : '';

    $staffsRows .= "<tr staff_id='{$staff->id}'>
    <td>{$staff->name}</td>
    <td>{$staff->sex}</td>
    <td>{$staff->dob}</td>
    <td>{$staff->position}</td>
    <td class='action'> $updateIcon $deleteIcon </td>
</tr>";
}

$staffsList = "<table class='list' id='select-staff'>$staffsRows</table>";

$section = <<<"SECTION"
<div id="staff" class="task">
    <div class="title">បុគ្គលិក</div>
    <div class="content">
        $staffsList
    </div>
</div>
SECTION;

$js = <<<"JS"
<script src="/static/js/$page.js"></script>
JS;

require __DIR__ . '/_base_/_base_.html.php';
