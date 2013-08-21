/**
 * @author Luis Santos <lsantoschavez@gmail.com>
 */
$(document).ready(function() {
   




//    $.fn.comboAdd = function(opciones) {
//
//        var defec = {
//            combo: "",
//            buttonAdd: "",
//            buttonRemove: "",
//            container: "",
//            input_response: "",
//            prueba: false,
//            data: []
//        };
//        $.extend(defec, opciones);
//        var buttonAdd = defec.buttonAdd;
//        var data_combo = defec.combo;
//        var data_container = defec.container;
//        var buttonRemove = defec.buttonRemove;
//        var input_response = defec.input_response;
//        
//        listaOpciones();
//        console.log((defec.data));
//
//
//
//        $(buttonAdd).click(function() {
//
//            var val_cbo = $(data_combo).val();
//            var validar = false;
//            if ((defec.data).length > 0) {
//
//                for (var i in (defec.data)) {
//                    if ((defec.data)[i][0] === val_cbo) {
//
//                        validar = false;
//                        break;
//                    } else {
//
//
//                        validar = true;
//                    }
//                }
//            } else {
//                validar = true;
//            }
//
//            if (validar === true) {
//                var seleccionado = $(data_combo + " option:selected").text();
//                if (seleccionado !== "") {
//
//                    var row = [];
//                    var text_cbo = $(data_combo + " option:selected").text();
//                    row.push(val_cbo);
//                    row.push(text_cbo);
//                    (defec.data).push(row);
//                    listaOpciones();
//                }
//            } else {
//                validar = false;
//            }
//
//        });
//        $(data_container + " > .item_cbo").live("click", function() {
//            $(data_container + " > .item_cbo").removeClass("item_cbo_selected");
//            $(this).addClass("item_cbo_selected");
//        });
//        $(buttonRemove).click(function() {
//            var posicion = $(data_container + " > .item_cbo_selected").attr("rel");
//            if (posicion !== undefined) {
//                (defec.data).splice(posicion, 1);
//                listaOpciones();
//            }
//
//        });
//        function listaOpciones() {
//            var data_combo_response = "";
//            var combo_response = "";
//            for (var i in (defec.data)) {
//                data_combo_response += "<div class='item_cbo' rel='" + i + "'>" + (defec.data)[i][1] + "</div>";
//                combo_response += (defec.data)[i][0] + "-" + (defec.data)[i][1] + "|";
//            }
//            combo_response = combo_response.substring(0, (combo_response.length) - 1);
//            combo_response = "<input type='hidden' name='" + data_combo.substring(1, combo_response.length) + "_response' value='" + combo_response + "'/>"
//            $(data_container).html(data_combo_response);
//            manda_json((defec.data));
//        }
//
//        function manda_json(data) {
//            var string_data = "";
//            var count = 0;
//            for (var row in data) {
//                count++;
//                string_data += data[row][0] + ",";
//            }
//            string_data = string_data.substring(0, (string_data.length - 1));
//            $(input_response).val(string_data);
//            console.log(string_data);
//        }
//        function limpia_data() {
//            $(data_container).html("");
//        }
//
//        function remover() {
//            $(buttonAdd).unbind("click");
//            $(buttonRemove).unbind("click");
//            $(data_container + " > .item_cbo").unbind("click");
//        }
//    }

});