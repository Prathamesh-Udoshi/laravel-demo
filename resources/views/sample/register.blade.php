<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form action="/student/register" method="post">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

    <table>
        <tr>
            <td>Name</td>
            <td><input type="text" name="name"></td>
        </tr>
        <tr>
            <td>Class</td>
            <td><input type="text" name="class"></td>
        </tr>
        <tr>
            <td colspan="2" aligh="center">
                <input type="submit" value="Register">
            </td>
        </tr>
    </table>
    </form>
</body>
</html>