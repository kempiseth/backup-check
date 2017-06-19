<?php

use PKEM\Model\Staff;

$staffUpdate = $_SESSION['user']->canUpdate() ?
"<form name='staff-update-form' method='post'>
  <input type='hidden' name='_ajax' value='editStaff'>
  <input type='hidden' name='staff_id' value='{$staff->staff_id}'>
  <fieldset>
    <legend>កែប្រែទិន្នន័យបុគ្គលិក</legend>
    <table id='staff-update-table' class='open'>
      <tr><td colspan='2'>
        <label class='mr20 active'><input type='radio' name='is_active' value='1'> សកម្ម </label>
        <label class='mr20 caution'><input type='radio' name='is_active' value='0'> អសកម្ម </label>
      </td></tr>
      <tr>
        <td><label for='name'>ឈ្មោះពេញ</label></td>
        <td><input type='text' id='name' name='name' value='{$staff->name}'
            placeholder='គោត្តនាម និង នាម' class='_input' required></td>
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
          pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD' value='{$staff->dob}'></td>
      </tr>
      <tr>
        <td><label for='phone'>ទូរស័ព្ទ</label></td>
        <td><input type='text' id='phone' name='phone' class='_input' required
          pattern='[\+]*\d{1,3}[\s-]?\d{1,3}[\s-]?\d{1,4}[\s-]?\d{1,4}' value='{$staff->phone}'></td>
      </tr>
      <tr>
        <td><label for='address'>អាសយដ្ឋាន</label></td>
        <td><textarea rows='3' id='address' name='address' class='_input' required>{$staff->address}</textarea></td>
      </tr>
      <tr>
        <td><label for='education'>កម្រិតការអប់រំ</label></td>
        <td><input type='text' id='education' name='education' class='_input' value='{$staff->education}'></td>
      </tr>
      <tr>
        <td><label for='skill'>ជំនាញ</label></td>
        <td><input type='text' id='skill' name='skill' class='_input' value='{$staff->skill}'></td>
      </tr>
      <tr>
        <td><label for='language'>ភាសា</label></td>
        <td><input type='text' id='language' name='language' class='_input' value='{$staff->language}'></td>
      </tr>
      <tr>
        <td><label for='position'>មុខតំណែង</label></td>
        <td><input type='text' id='position' name='position' class='_input' required value='{$staff->position}'></td>
      </tr>
      <tr>
        <td><label for='department'>នាយកដ្ឋាន</label></td>
        <td><input type='text' id='department' name='department' class='_input' value='{$staff->department}'></td>
      </tr>
      <tr>
        <td><label for='enroll_date'>ថ្ងៃចូលធ្វើការ</label></td>
        <td><input type='text' id='enroll_date' name='enroll_date' class='_input' required placeholder='YYYY-MM-DD'
          pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD' value='{$staff->enroll_date}'></td>
      </tr>
      <tr>
        <td><label for='salary'>ប្រាក់ខែ</label></td>
        <td><input type='number' min='100' step='0.01' id='salary' name='salary'
          class='_input caution' required placeholder='#.##' title='#.##' value='{$staff->salary}'></td>
      </tr>
      <tr><td colspan='2'><button type='submit'>កែប្រែបុគ្គលិក</button></td></tr>
    </table>
  </fieldset>
</form>" : '';

$section = <<<"SECTION"
<a class="back-button" href="/human-resource">ត្រលប់ក្រោយ</a>
<div id="staff" class="task">
    <div class="title">បុគ្គលិក :: $staff->name</div>
    <div class="content">
        $staffUpdate
    </div>
</div>
SECTION;

$js = <<<"JS"
<script>
$('input[name=is_active][value={$staff->is_active}]').prop('checked',true);
$('select[name=sex] option[value={$staff->sex}]').prop('selected',true);
$('input[name=is_active]').change(function() {
    var checked_color = $('input[name=is_active]:checked').css('color');
    $('input#name').css('color', checked_color);
}).change();
</script>
JS;

require __DIR__ . '/_base_/_base_.html.php';
