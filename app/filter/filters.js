/**
 * A collection of filters for Brazilian standards
 * @version v1.1.0 - 2014-09-29
 * @author Igor Costa
 * @link https://github.com/igorcosta/ng-filters-br
 * @license Apache License 2.0
 **/

 'use strict';
// Source: dist/.temp/brasil/filters/cep.js
// $app.filter('cep', function () {
//   return function (input) {
//     var str = input + '';
//     str = str.replace(/\D/g, '');
//     str = str.replace(/^(\d{2})(\d{3})(\d)/, '$1.$2-$3');
//     return str;
//   };
// });
// // Source: dist/.temp/brasil/filters/cnpj.js
// $app.filter('cnpj', function () {
//   return function (input) {
//     // regex créditos Matheus Biagini de Lima Dias
//     var str = input + '';
//     str = str.replace(/\D/g, '');
//     str = str.replace(/^(\d{2})(\d)/, '$1.$2');
//     str = str.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
//     str = str.replace(/\.(\d{3})(\d)/, '.$1/$2');
//     str = str.replace(/(\d{4})(\d)/, '$1-$2');
//     return str;
//   };
// });
// Source: dist/.temp/brasil/filters/cpf.js
// angular.module('brasil.filters', []).filter('cpf', function () {
//   return function (input) {
//     var str = input + '';
//     str = str.replace(/\D/g, '');
//     str = str.replace(/(\d{3})(\d)/, '$1.$2');
//     str = str.replace(/(\d{3})(\d)/, '$1.$2');
//     str = str.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
//     return str;
//   };
// });

// Source: dist/.temp/brasil/filters/tel.js
$app.filter('tel', function () {

	return function (input) {

		var str = input + '';
		str = str.replace(/\D/g, '');

		if (str.length === 11) {
			str = str.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
		} else {
			str = str.replace(/^(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
		}

		return str;
	};
});

/**
 * Filter to put data in Brazil format.
 */
$app.filter('dateBr', function () {

	return function (input) {

		let data = input.split(' ');
		return data[0].split('-').reverse().join().replace(/\D/g, '/') + ' ' + data[1];
	};
});
