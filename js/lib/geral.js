/*
 * Copyright (C) 2014
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Classe reponsável por scripts do sistema fulo.
 * @class Geral
 * @author Victor Eduardo Barreto
 * @date Nov 3, 2014
 * @version 1.0
 */
var Geral = {
    /**
     * Método construtor
     * @constructor
     * @author Victor Eduardo Barreto
     * @date Nov 3, 2014
     * @version 1.0
     */
    geral: function () {
        Geral.voltarTopo();
        Geral.niceScroll();
    },
    /**
     * Método responsável pelo funcionamento do botão de voltar ao topo da página.
     * @name voltarTopo
     * @author Victor Eduardo Barreto
     * @date Nov 3, 2014
     * @version 1.0
     */
    voltarTopo: function () {

        $(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });

        // apresenta ou esconde o tip
        $('#back-to-top').click(function () {
            $('#back-to-top').tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        $('#back-to-top').tooltip('hide');
    },
    /**
     * Método responsável por mudar a barra de rolagem da pagina.
     * @name niceScroll
     * @author Victor Eduardo Barreto
     * @date Apr 4, 2015
     * @version 1.0
     */
    niceScroll: function () {

        // inicia o scroll em toda a pagina.
        $("html").niceScroll({
            cursorcolor: "#424242",
            cursorwidth: "7px",
            hwacceleration: true,
        });

    },
    /**
     * Método responsável por mudar a barra de rolagem da pagina.
     * @name niceScroll
     * @author Victor Eduardo Barreto
     * @date Apr 4, 2015
     * @version 1.0
     */
//    flashMessage: function ($tipo, $mensagem) {
//        
//        $('#flashmessage').
//    }

}

$(document).ready(function () {
    Geral.geral();
});