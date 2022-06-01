/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';


// /* Change header style on scroll
//     --------------------------------------------- */
// const siteHeader = document.querySelector( '#main-header' );
// let scrolledHeader = false;
//
// function toggleHeader() {
//     const siteHeaderTop = 0;
//     const scrollPosition = document.querySelector( 'html' ).scrollTop;
//     if ( ! scrolledHeader && scrollPosition > siteHeaderTop ) {
//         siteHeader.classList.add( 'site-header--small' );
//         scrolledHeader = ! scrolledHeader;
//     } else if ( scrolledHeader && scrollPosition <= siteHeaderTop ) {
//         siteHeader.classList.remove( 'site-header--small' );
//         scrolledHeader = ! scrolledHeader;
//     }
// }
//
// if ( siteHeader ) {
//     toggleHeader();
//     window.onscroll = () => {
//         toggleHeader();
//     };
// }