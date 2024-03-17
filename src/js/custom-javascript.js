// Add your custom JS here.

/* Function to add styles after page load */
// outputting empty <style> tags at the moment...
// jQuery(function($){
//     var navListItems = document.querySelectorAll('ul.nav-list li');
// console.log('here'+navListItems);
//     navListItems.forEach((item, index) => {
//         var style = document.createElement('style');
//         document.head.appendChild(style);
//         style.sheet.insertRule(`ul.nav-list li:nth-child(${index + 1}) { view-transition-name: navLink-${index + 1}; }`, 0);
//     });
// },9999
// );


AOS.init({
    once: true,
    easing: 'ease-in',
});


// jQuery(function($){
//     var btn = $('#to-top');

//     $(window).scroll(function () {
//       if ($(window).scrollTop() > 300) {
//         btn.addClass('show');
//       } else {
//         btn.removeClass('show');
//       }
//     });

//     btn.on('click', function (e) {
//       e.preventDefault();
//       $('html, body').animate({ scrollTop: 0 }, '300');
//     });
// },9999);

