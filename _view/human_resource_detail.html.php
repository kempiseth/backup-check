<?php

use PKEM\Model\Staff;

$staffDetails = '';
$status_text = '';
$staffShifts = '';
$newShift = '';
$today = date('Y-m-d');

if ($details) {
    $id = Staff::formatId($details->id);
    $status = $details->is_active ? 'active' : 'caution';
    $status_text = $details->is_active ? 'សកម្ម' : 'ឈប់ថ្ងៃ '.$details->leave_date;
    $staffDetails = "<table staff_id='{$details->staff_id}' class='key-value list'>
  <tr><td colspan='2'><img src='{$details->photo}'></td></tr>
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
}

foreach ($shifts as $shift) {
    $shiftBlock = "
<table class='key-value list'>
  <tr><td></td><td></td></tr>
</table>";
    $staffShifts .= $shiftBlock;
}

if($_SESSION['user']->canInsert()) {
    $newShift = "<button id='new-shift-btn'>បន្ថែមថ្មី</button>";
}

$section = <<<"SECTION"
<a class="back-button" href="/human-resource">ត្រលប់ក្រោយ</a>
<div id="staff-detail" class="task">
    <div class="title">ព័ត៌មានលម្អិត | $id | <span class="$status">$status_text</span> </div>
    <div class="content">
        $staffDetails
    </div>
</div>
<div id="position-shift" class="task">
    <div class="title">តួនាទី និង ម៉ោងធ្វើការ</div>
    <div class="content">
        <div class="shifts">
            $staffShifts
            <form id="new-shift-form" method="post" style="display:none">
              <table>
                <tr>
                  <td><label for="position">តួនាទី</label></td>
                  <td><input type="text" id="position" name="position" class="_input" required></td>
                </tr>
                <tr>
                  <td><label for='salary'>ប្រាក់ខែ</label></td>
                  <td><input type='number' min='100' step='0.01' id='salary' name='salary'
                    class='_input caution' required placeholder='#.##' title='#.##'></td>
                </tr>
                <tr>
                  <td><label for='start_date'>ថ្ងៃផ្ដើម</label></td>
                  <td><input type='text' id='start_date' name='start_date' class='_input' required placeholder='YYYY-MM-DD'
                    pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD' value='$today'></td>
                </tr>
                <tr>
                  <td><label for='end_date'>ថ្ងៃបញ្ចប់</label></td>
                  <td><input type='text' id='end_date' name='end_date' class='_input' placeholder='YYYY-MM-DD'
                    pattern='[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' title='YYYY-MM-DD'></td>
                </tr>
                <tr>
                  <td><label>ថ្ងៃធ្វើការ</label></td>
                  <td>
                    <label><input type='checkbox' name='work_days[]' value='mon' checked> ច័ន្ទ</label>
                    <label><input type='checkbox' name='work_days[]' value='tue' checked> អង្គារ</label>
                    <label><input type='checkbox' name='work_days[]' value='wed' checked> ពុធ</label>
                    <label><input type='checkbox' name='work_days[]' value='thu' checked> ព្រហស្បតិ៍</label>
                    <label><input type='checkbox' name='work_days[]' value='fri' checked> សុក្រ</label>
                    <label><input type='checkbox' name='work_days[]' value='sat'> សៅរ៍</label>
                    <label><input type='checkbox' name='work_days[]' value='sun'> អាទិត្យ</label>
                  </td>
                </tr>
                <tr>
                  <td><label>ម៉ោងធ្វើការ</label></td>
                  <td>ពីម៉ោង <input type='time' name='work_times[]' required>
                      ដល់ម៉ោង <input type='time' name='work_times[]' required></td>
                </tr>
                <tr><td colspan='2'>
                  <button id='cancel-shift-btn' type='reset'>បោះបង់</button>
                  <button id='submit-shift-btn' type='submit'>បញ្ជូន</button></td></tr>
              </table>
              <input type="hidden" name="staff_id" value="$details->id">
            </form>
        </div>
        $newShift
    </div>
</div>
SECTION;

$js = <<<"JS"
<script>
$('button#new-shift-btn').click(function(){
  $(this).hide('slow');
  var form = $('form#new-shift-form');
  form.show('slow'); form.find('input:first').focus();
});
$('button#cancel-shift-btn').click(function(){
  $('form#new-shift-form').hide('slow');
  $('button#new-shift-btn').show('slow');
});
</script>
JS;

require __DIR__ . '/_base_/_base_.html.php';
