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
 * Classe reponsável por scripts do banner da página inicial
 * @class Banner
 * @author Victor Eduardo Barreto
 * @date Nov 3, 2014
 * @version 1.0
 */
var Banner = {
    /**
     * Método construtor
     * @constructor
     * @author Victor Eduardo Barreto
     * @date Nov 3, 2014
     * @version 1.0
     */
    banner: function () {
        Banner.panning();
    },
    /**
     * Método responsável por aplicar o efeito motio no banner da tela inicial.
     * @name panning
     * @author Victor Eduardo Barreto
     * @date Nov 3, 2014
     * @version 1.0
     */
    panning: function () {

        // procura o elemento.
        var element = document.querySelector('#panning');

        // configura o efeito.
        var panning = new Motio(element, {
            fps: 25, // Frames per second. More fps = higher CPU load.
            speedX: 25   // Negative horizontal speed = panning to left.
        });

        // inicia a animação
        panning.play();

    }
}

// chama o construtor.
$(document).ready(function () {
    Banner.banner();
});
