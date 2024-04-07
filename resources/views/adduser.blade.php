<html>
<form action="{{route('users.save')}}" method="post">
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<input type="text" name="name"><br>
<input type="text" name="email"><br>
<input type="submit" name="save">
</form>
</html>