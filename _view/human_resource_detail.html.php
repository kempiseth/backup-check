<?php

use PKEM\Model\Staff;

$staffDetails = '';
$status_text = '';
$staffShifts = '';
$dayOffs = '';
$addShiftBtn = '';
$editStaffBtn = '';
$today = date('Y-m-d');

if ($details) {
    $id = Staff::formatId($details->id);
    $status = $details->is_active ? 'active' : 'caution';
    $status_text = $details->is_active ? 'នៅធ្វើការ' : 'បានលាឈប់នៅថ្ងៃ '.$details->leave_date;
    $leave_reason = $details->is_active ? '' :
        "<tr><td>មូលហេតុលាឈប់</td><td class='$status'>{$details->leave_reason}</td></tr>";

    $staffDetails = "<table staff_id='{$details->staff_id}' class='key-value list'>
  <tr><td colspan='2'><img src='{$details->photo}'></td></tr>
  $leave_reason
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
  <tr><td>ប្រាក់ខែ</td><td class='caution'>{$details->salary}</td></tr>
</table>";
}

foreach ($shifts as $shift) {
    $work_days = json_decode($shift->work_days);
    $work_days = array_map(['PKEM\Model\Staff','KHdays'], $work_days);
    $work_days = join(' ', $work_days);

    $work_times = json_decode($shift->work_times);
    $work_times = join(' ដល់ ', $work_times);

    $staffShifts .= "
<div class='block-wrap' staff_id='$shift->staff_id' shift_id='$shift->id'>
  <div class='action-wrap'>
    <button class='action-btn' action='update'>កែប្រែ</button>
  </div>
  <table class='key-value list item'>
    <tr><td>តួនាទី</td><td key='position'>$shift->position</td></tr>
    <tr><td>ប្រាក់ខែ</td><td key='salary' class='caution'>$shift->salary</td></tr>
    <tr><td>ថ្ងៃផ្ដើម</td><td key='start_date'>$shift->start_date</td></tr>
    <tr><td>ថ្ងៃបញ្ចប់</td><td key='end_date'>$shift->end_date</td></tr>
    <tr><td>ថ្ងៃធ្វើការ</td><td key='work_days' _value='$shift->work_days'>$work_days</td></tr>
    <tr><td>ម៉ោងធ្វើការ</td><td key='work_times' _value='$shift->work_times'>$work_times</td></tr>
  </table>
</div>";
}

foreach ($day_offs as $day_off) {
    $dayOffs .= "
<div class='block-wrap' staff_id='$day_off->staff_id' day_off_id='$day_off->id'>
  <table class='key-value list item'>
    <tr><td>ពីថ្ងៃ</td><td key='from_date'>$day_off->from_date</td></tr>
    <tr><td>ទៅដល់ថ្ងៃ</td><td key='to_date'>$day_off->to_date</td></tr>
    <tr><td>សេចក្ដីពិពណ៌នា</td><td key='description'>$day_off->description</td></tr>
  </table>
</div>";
}

if($_SESSION['user']->canInsert()) {
    $addShiftBtn = "<button id='new-shift-btn'>បន្ថែមថ្មី</button>";
}
if($_SESSION['user']->canUpdate() && $details) {
    $editStaffBtn = "<a class='link-button' href='/human-resource/edit?staff_id={$details->staff_id}'>កែប្រែបុគ្គលិក</a>";
}

$section = <<<"SECTION"
<a class="link-button" href="/human-resource">ត្រលប់ក្រោយ</a>
$editStaffBtn
<div id="staff-detail" class="task">
    <div class="title">ព័ត៌មានលម្អិត | $id <br><span class="$status">$status_text</span> </div>
    <div class="content">
        $staffDetails
    </div>
</div>
<div id="position-shift" class="task">
    <div class="title">តួនាទី និង ម៉ោងធ្វើការ</div>
    <div class="content">
        <div class="shifts">
            $staffShifts
            <form id="shift-form" method="post" style="display:none">
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
        $addShiftBtn
    </div>
</div>
<div id="day-off-list" class="task">
    <div class="title">ប្រវត្តការសុំច្បាប់</div>
    <div class="content">
        <div class="day-offs">
            $dayOffs
        </div>
    </div>
</div>
SECTION;

$js = <<<"JS"
<script>
$('button#new-shift-btn').click(function(){
  $(this).hide('slow');
  var form = $('form#shift-form');
  form.show('slow'); form.find('input:first').focus();
});
$('button#cancel-shift-btn').click(function(){
  //INSERT
  $('form#shift-form').hide('slow');
  $('button#new-shift-btn').show('slow');

  //UPDATE
  $('div.shift-wrap').show('slow');
  $('form#shift-form input#shift_id').remove();
});
$('div.shifts').on('click', '.action-btn', function(){
  var action = $(this).attr('action');
  var shift_block = $(this).parent().parent();
  var shift_table = shift_block.find('table');
  var shift_id = shift_block.attr('shift_id');

  if (action == 'update') {
    var shift_form = $('form#shift-form');
    var position = shift_table.find("td[key='position']").text();
    var salary = shift_table.find("td[key='salary']").text();
    var start_date = shift_table.find("td[key='start_date']").text();
    var end_date = shift_table.find("td[key='end_date']").text();
    var work_days = JSON.parse( shift_table.find("td[key='work_days']").attr('_value') );
    var work_times = JSON.parse( shift_table.find("td[key='work_times']").attr('_value') );

    $('div.shift-wrap').hide('slow');
    $('button#new-shift-btn').click();
    shift_form.append('<input type="hidden" id="shift_id" name="shift_id" value="' + shift_id + '">');

    shift_form.find('input#position').val(position);
    shift_form.find('input#salary').val(salary);
    shift_form.find('input#start_date').val(start_date);
    shift_form.find('input#end_date').val(end_date);
    shift_form.find('input[name="work_days[]"]').each(function(){
      $(this).prop('checked', $.inArray($(this).val(), work_days) != -1);
    });
    shift_form.find('input[name="work_times[]"]').each(function(index){
      $(this).val(work_times[index]);
    });
  }
});
</script>
JS;

require __DIR__ . '/_base_/_base_.html.php';
