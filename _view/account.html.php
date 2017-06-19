<?php

$username = @$_SESSION['user']->username;
$changePasswordForm = "<form name='change-password-form' method='post' autocomplete='off'>
  <fieldset>
    <legend>ផ្លាស់ប្តូរពាក្យសម្ងាត់</legend>
    <table id='change-password-table'>
      <tr>
        <td><label for='username'>អ្នកប្រើប្រាស់</label></td>
        <td><input type='text' id='username' name='username' class='_input' readonly value='$username'></td>
      </tr>
      <tr>
        <td><label for='password'>ពាក្យសម្ងាត់​ថ្មី</label></td>
        <td><input type='password' id='password' name='password' class='_input' required autocomplete='new-password'></td>
      </tr>
      <tr>
        <td><label for='confirm-password'>ពាក្យ​សម្ងាត់​ម្តង​ទៀត</label></td>
        <td><input type='password' id='confirm-password' name='confirm-password' class='_input' required autocomplete='new-password'></td>
      </tr>
      <tr>
        <td colspan='2'><button type='submit'>ផ្លាស់ប្តូរ</button></td>
      </tr>
    </table>
  </fieldset>
</form>";

$section = <<<"SECTION"
<div id="account" class="task">
  <div class="title">ការកំណត់​គណនី :: <span class="active">$username</span></div>
  <div class="content">
    $changePasswordForm
  </div>
</div>
SECTION;

require __DIR__ . '/_base_/_base_.html.php';
