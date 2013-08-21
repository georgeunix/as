/* 
 Document   : estilo-grilla
 Created on : 22-jul-2013, 12:11:21
 Author     : Luis Antonio Santos Chavez
 Description:
 plugin.
 */

$.fn.novoGrid = function(opciones) {

//                             name: 'CODIGO', index: 'CODIGO', width: 50, resizable: false, align: "center"},
//                            {name: 'TIPO_DE_DOCUMENTO', index: 'TIPO_DE_DOCUMENTO', width: 120, resizable: false, sortable: true, align: "center"},
//                            {name: 'FECHA_CREACION', index: 'FECHA_CREACION', width: 180, align: "center"},
//                            {name: 'CICLO', index: 'CICLO', width: 160, align: "center"},
//                            {name: 'INDICATIVO_DEL_DOCUMENTO', index: 'INDICATIVO_DEL_DOCUMENTO', width: 200, align: "center"},
//                            {name: 'ASUNTO', index: 'ASUNTO', width: 120, resizable: false, align: "center"},
//                            {name: 'USUARIO', index: 'USUARIO', width: 150, resizable: false, sortable: true, align: "center"},
//                            {name: 'ACCION', index: 'ACCION', width: 150, resizable: false, sortable: true, align: "center"}











    var defec = {grid: [],
        columns: [{title: "CODIGO", left: "10px", width: "10px", align: "center"},
            {title: "JUEGO", left: "29px", width: "69px", align: "center"},
            {title: "Fc.COMPRA", left: "114px", width: "76px", align: "center"},
            {title: "PREMIOS", left: "200px", width: "117px", align: "center"},
            {title: "CADUCIDAD", left: "342px", width: "80px", align: "center"},
            {title: "COBRAR", left: "465px", width: "90px", align: "center"}]};
    $.extend(defec, opciones);

    var content_grid = dibuja_grilla() +  paginator_grid();
    //acumulados() +
    this.html(content_grid);


    function dibuja_grilla() {

        var dato = defec.columns;
        var count_column = 0;
        var cabecera = "<div class='columns-grid'>";

        for (var i in dato) {
            cabecera += "<div class='column' style='"
                    + "left:" + dato[i].left + ";"
                    + "width:" + dato[i].width + ";"
                    + "text-align:" + dato[i].align + ";'>"
                    + dato[i].title
                    + "</div>";
            count_column++;
        }
        cabecera += "</div>";

        var content = "";
        var contador = 0;
        for (var v in defec.grid) {
            contador++;
            var classCss = "row-grid";
            var style = "";
            if ((parseInt(v) + 1) % 2 === 0) {
                classCss += "2";
            }
            if (contador > 5) {
                style = "style='display:none;'";
            }
            content += "<div class='state-row " + classCss + "'" + style + ">";
            content += "<div class='row' style='left:" + dato[0].left + ";width:" + dato[0].width + ";text-align:" + dato[0].align + ";'>" + defec.grid[v][0] + "</div>";
            content += "<div class='row' style='left:" + dato[1].left + ";width:" + dato[1].width + ";text-align:" + dato[1].align + ";'>" + defec.grid[v][1] + "</div>";
            content += "<div class='row' style='left:" + dato[2].left + ";width:" + dato[2].width + ";text-align:" + dato[2].align + ";'>" + defec.grid[v][2] + "</div>";
            content += "<div class='row' style='left:" + dato[3].left + ";width:" + dato[3].width + ";text-align:" + dato[3].align + ";'>" + defec.grid[v][3] + "</div>";
            content += "<div class='row' style='left:" + dato[4].left + ";width:" + dato[4].width + ";text-align:" + dato[4].align + ";'>" + defec.grid[v][4] + "</div>";
            content += "<div class='row' style='left:" + dato[5].left + ";width:" + dato[5].width + ";text-align:left;'>" + defec.grid[v][5] + "</div>";
            content += "</div>";
        }
        var content_grilla = cabecera + content;

        return content_grilla;
    }
    function acumulados() {
        var acumulados = defec.acu;
        var resp = "";
        for (var t in acumulados) {
            resp += "<div class='row-acu'>";
            resp += "<div class='acu-item' style='left:10px;width:190px;text-align:left' ><b>" + acumulados[t][0] + "</b></div>";
            resp += "<div class='acu-item' style='left:200px;width:117px;text-align:center'>" + acumulados[t][1] + "</div>";
            resp += "<div class='acu-item' style='left:465px;width:90px;text-align:left'>" + acumulados[t][2] + "</div>";
            resp += "</div>";
        }
        return resp;
    }


    function paginator_grid() {

        var data = defec.grid;
        var count_rows = data.length;
        var links = "";
        var cont = 0;
        var style = "";
        var pos1 = 0;
        var pos2 = 1;
        var pos3 = 2;
        var pos4 = 3;
        var pos5 = 4;

        for (var i = 0; i < count_rows; i++) {

            if (i === 0) {
                style = "num_page_off";
            }
            else {
                style = "num_page_on";
            }
            if (i % 7 === 0) {
                cont++;
                links += "&nbsp;<a class='num-pag lnk " + style + " ' id='w" + pos1 + "-" + pos2 + "-" + pos3 + "-" + pos4 + "-" + pos5 + "' rel='" + pos1 + "-" + pos2 + "-" + pos3 + "-" + pos4 + "-" + pos5 + "' >" + cont + "</a>&nbsp;";

                pos1 = pos1 + 5;
                pos2 = pos2 + 5;
                pos3 = pos3 + 5;
                pos4 = pos4 + 5;
                pos5 = pos5 + 5;
            }
        }
        var paginador = "<div class='draw-paginator'><span class='indice_page'>1</span> de " + cont + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<" + links + "></div>";
        return paginador;
    }

    $(".num-pag").bind("click", function() {
        var cadena_pos = $(this).attr("rel");
        var id = $(this).attr("id");
        var posiciones = cadena_pos.split("-");
        $(".state-row").css("display", "none");
        for (var num in posiciones) {
            $(".state-row").eq(posiciones[num]).css("display", "block");
        }
        $(".indice_page").html($("#" + id).html());
        $(".lnk").removeClass("num_page_on");
        $(".lnk").removeClass("num_page_off");
        $(".lnk").addClass("num_page_on");
        $("#" + $(this).attr("id")).removeClass("num_page_on");
        $("#" + $(this).attr("id")).addClass("num_page_off");

    });


};