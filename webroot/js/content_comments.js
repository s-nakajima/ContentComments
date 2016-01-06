/**
 * @fileoverview ContentComments Javascript
 * @author mutaguchi@opensource-workshop.jp (Mitsuru Mutaguchi)
 */


/**
 * ContentComments Javascript
 *
 * @param {string} Controller name
 * @param {function(scope)} Controller
 */
NetCommonsApp.controller('ContentComments',
                         function($scope) {

      /**
       * Initialize
       *
       * @return {void}
       */
      $scope.initialize = function() {

      };

      /**
       * もっと見る
       *
       * @return {void}
       */
      $scope.more = function() {
        $('article.comment:hidden').removeClass('hidden');
        $('button.more').hide(0);
      };
    });

