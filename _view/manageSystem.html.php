<?php

use PKEM\Model\User;

$usersRows = '';
foreach ($users as $user) {
    $deleteIcon = ($user->username == User::ADMIN_USER || 
        $user->username == $_SESSION['user']->username) ? 
        '' : "<img class='icon' action='delete' src='/static/image/delete.jpg'>";
    $usersRows .= "<tr userid='{$user->id}'>
    <td>{$user->username}</td>
    <td>{$user->roles}</td>
    <td>{$user->date}</td>
    <td class='action'>$deleteIcon</td>
</tr>";
}

$usersTable = "<table class='list' id='select-user'>$usersRows</table>";
$insertForm = $_SESSION['user']->canInsert() ? 
"<form name='insert-form' method='post' autocomplete='off'>
<fieldset><legend>Add a new system user</legend>
<table id='insert-user'>
<tr>
    <td><label for='username'>Username</label></td>
    <td><input type='text' id='username' name='username' class='_input' required></td>
</tr>
<tr>
    <td><label for='password'>Password</label></td>
    <td><input type='password' id='password' name='password' class='_input' required autocomplete='new-password'></td>
</tr>
<tr>
    <td colspan='2' id='roles'>
        <input type='hidden' name='roles[]' value='select'>
        <label><input type='checkbox' name='roles[]' value='select' checked disabled> Select</label>
        <label><input type='checkbox' name='roles[]' value='insert'> Insert</label>
        <label><input type='checkbox' name='roles[]' value='update'> Update</label>
        <label><input type='checkbox' name='roles[]' value='delete'> Delete</label>
    </td>
</tr>
<tr><td colspan='2'><button type='submit'>Add user</button></td></tr>
</table></fieldset></form>" : '';

$nav = <<<"NAV"
<a href="/human-resource/">Human Resource</a>
NAV;

$section = <<<"SECTION"
<div id="user" class="task">
    <div class="title">Users</div>
    <div class="content">
        $usersTable
        $insertForm
    </div>
</div>
SECTION;

$js = <<<"JS"
<script src="/static/js/$page.js"></script>
JS;

require __DIR__ . '/_base_/_base_.html.php';
