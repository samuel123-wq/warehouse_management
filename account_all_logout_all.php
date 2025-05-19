<?php
session_start();
session_destroy();
header("location: http://localhost/warehouse/main");
?>
<script type="text/javascript">
 
        location.reload(true);

</script>