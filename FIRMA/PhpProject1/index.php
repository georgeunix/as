<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>FIRMA DIGITAL desde PHP</title>
        <script type="text/javascript">

            function firmarDocumento() {
                var var1 = "2323"
                alert("firmaDocumento")
                var strWidth= 850;
                var strHeight = 800;
                var leftStr = (screen.width-strWidth)/2;
                var topStr = (screen.height-strHeight)/2-50;
                windowProperties = "toolbar=no,menubar=no,scrollbars=yes,resizable=1,statusbar=no,height="+strHeight+",width="+strWidth+",left="+leftStr+",top="+topStr+"";
                popup = window.open("/PhpProject1/firmarDocumento.php?doc="+var1,"popup1", windowProperties);
                popup.focus();
                return false;
            }
            function actualizar(){
                document.getElementById("if56").src = "test2.jsp";
                //document.getElementById('if56').contentWindow.location.reload(true);
            }

        </script>
    </head>
    <body>
        <?php
        echo "FIRMA DIGITAL";
        ?>
        <button onclick="firmarDocumento()">Firmar.pdf</button>&nbsp;
        
    </body>
</html>
