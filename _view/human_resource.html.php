<?php

use PKEM\Model\Staff;

$staffsRows = '';
foreach ($staffs as $staff) {
    $status = $staff->is_active ? 'active' : 'caution';
    $deleteIcon = $_SESSION['user']->canDelete() ?
        "<img class='icon' action='delete' src='/static/image/delete.jpg' title='Remove'>" : '';
    $updateIcon = $_SESSION['user']->canUpdate() ?
        "<img class='icon' action='update' src='/static/image/update.jpg' title='Edit'>" : '';

    $staffsRows .= "<tr staff_id='{$staff->id}'>
    <td class='$status'>{$staff->name}</td>
    <td>{$staff->sex}</td>
    <td>{$staff->dob}</td>
    <td>{$staff->position}</td>
    <td class='action'> $updateIcon $deleteIcon </td>
</tr>";
}
$staffsList = "<table class='list' id='select-staff'>$staffsRows</table>";

$staffInsert = $_SESSION['user']->canInsert() ?
"<form name='staff-insert-form' method='post'>
  <fieldset>
    <legend>បញ្ចូលបុគ្គលិកថ្មី</legend>
    <table id='staff-insert-table'>
      <tr>
        <td><label for='name'>ឈ្មោះពេញ</label></td>
        <td><input type='text' id='name' name='name'
            placeholder='គោត្តនាម និង នាម' class='_input active' required></td>
      </tr>
      <tr>
        <td><label for='sex'>ភេទ</label></td>
        <td><select id='sex' name='sex' class='_input'>
          <option value='ប្រុស'>ប្រុស</option>
          <option value='ស្រី'>ស្រី</option>
        </select></td>
      </tr>
      <tr>
        <td><label for='dob'>ថ្ងៃខែឆ្នាំកំណើត</label></td>
        <td><input type='text' id='dob' name='dob' class='_input' required placeholder='YYYY-MM-DD'
          pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD'></td>
      </tr>
      <tr>
        <td><label for='phone'>ទូរស័ព្ទ</label></td>
        <td><input type='text' id='phone' name='phone' class='_input' required
          pattern='[\+]*\d{1,3}[\s-]?\d{1,3}[\s-]?\d{1,4}[\s-]?\d{1,4}'></td>
      </tr>
      <tr>
        <td><label for='address'>អាសយដ្ឋាន</label></td>
        <td><textarea rows='3' id='address' name='address' class='_input' required></textarea></td>
      </tr>
      <tr>
        <td><label for='education'>កម្រិតការអប់រំ</label></td>
        <td><input type='text' id='education' name='education' class='_input'></td>
      </tr>
      <tr>
        <td><label for='skill'>ជំនាញ</label></td>
        <td><input type='text' id='skill' name='skill' class='_input'></td>
      </tr>
      <tr>
        <td><label for='language'>ភាសា</label></td>
        <td><input type='text' id='language' name='language' class='_input'></td>
      </tr>
      <tr>
        <td><label for='position'>មុខតំណែង</label></td>
        <td><input type='text' id='position' name='position' class='_input' required></td>
      </tr>
      <tr>
        <td><label for='department'>នាយកដ្ឋាន</label></td>
        <td><input type='text' id='department' name='department' class='_input'></td>
      </tr>
      <tr>
        <td><label for='enroll_date'>ថ្ងៃចូលធ្វើការ</label></td>
        <td><input type='text' id='enroll_date' name='enroll_date' class='_input' required placeholder='YYYY-MM-DD'
          pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD'></td>
      </tr>
      <tr>
        <td><label for='salary'>ប្រាក់ខែ</label></td>
        <td><input type='number' min='100' step='0.01' id='salary' name='salary'
          class='_input caution' required placeholder='#.##' title='#.##'></td>
      </tr>
      <tr><td colspan='2'><button type='submit'>បញ្ចូលបុគ្គលិក</button></td></tr>
    </table>
  </fieldset>
</form>" : '';

$section = <<<"SECTION"
<div id="staff" class="task">
    <div class="title">បុគ្គលិក</div>
    <div class="content">
        $staffsList
        $staffInsert
    </div>
</div>
SECTION;

$js = <<<"JS"
<script src="/static/js/$page.js"></script>
JS;

require __DIR__ . '/_base_/_base_.html.php';
