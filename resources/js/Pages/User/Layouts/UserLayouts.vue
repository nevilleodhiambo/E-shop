<script setup>
import { onMounted } from "vue";
import { initFlowbite } from "flowbite";

import Header from "./Header.vue";

import Hero from "./Hero.vue";

import Footer from "./Footer.vue";
// import Footer from "@/Pages/admin/Components/Footer.vue";

// initialize components based on data attribute selectors
onMounted(() => {
  initFlowbite();
});

defineProps({
  products:Array
})

// const products = [
//   {
//     id: 1,
//     name: 'Basic Tee',
//     href: '#',
//     imageSrc: 'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg',
//     imageAlt: "Front of men's Basic Tee in black.",
//     price: '$35',
//     color: 'Black',
//   },
//   {
//     id: 2,
//     name: 'Basic Tee',
//     href: '#',
//     imageSrc: 'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-02.jpg',
//     imageAlt: "Front of men's Basic Tee in black.",
//     price: '$35',
//     color: 'Black',
//   },
//   {
//     id: 3,
//     name: 'Basic Tee',
//     href: '#',
//     imageSrc: 'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-03.jpg',
//     imageAlt: "Front of men's Basic Tee in black.",
//     price: '$35',
//     color: 'Black',
//   },
//   {
//     id: 4,
//     name: 'Basic Tee',
//     href: '#',
//     imageSrc: 'https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-04.jpg',
//     imageAlt: "Front of men's Basic Tee in black.",
//     price: '$35',
//     color: 'Black',
//   },
// ]
</script>

<template>

  <Header></Header>



  <Hero></Hero>

  <slot/>

  <Footer/>
  

</template>