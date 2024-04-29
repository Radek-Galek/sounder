<template>
    <Header/>

    <div class="homepage-hero h-[calc(100svh-88px)]">
        <div class="site-container flex flex-col items-center justify-end pb-80 h-full">
            <div class="text-container block my-auto text-[#070707] font-bold">
                <h1 class="text-center">Find Similar Songs</h1>
                <h5 class="text-center mt-20">Explore new tracks that match your vibe.</h5>
            </div>

            <div class="search-container bg-white w-full rounded-[20px] px-24 py-32 md:p-48 flex flex-col md:flex-row gap-12 md:gap-24">
                <input type="text" v-model="searchText" placeholder="Song name">

                <button class="btn !w-full md:!w-auto" @click="navigateToSearch"><span><div data-content="Search">Search</div></span></button>
            </div>
        </div>
    </div>

    <section class="fifty-fifty my-60 md:my-90 xl:my-120">
        <div class="site-container">
            <div class="grid md:grid-cols-2 w-full h-full gap-40 xl:gap-80">
                <div class="image-container relative h-auto md:h-full aspect-[3/2] md:aspect-auto w-full">
                    <img class="absolute md:relative w-full h-full md:h-auto rounded-[28px]" src="../assets/image-1.webp" alt="">
                </div>

                <div class="text-container flex flex-col justify-center md:pt-32 md:pb-60">
                    <h6 class="mb-12 md:mb-20 text-white block px-18 py-8 leading-1 rounded-[20px] bg-[#81d6fd] w-fit uppercase text-9 md:text-11">New feature</h6>

                    <h2 class="md:max-w-[520px]">Account info</h2>

                    <div class="copy mt-28 md:max-w-[575px]">
                        <p>We have created a new feature that will allow you to see details about your spotify account, and create a playslist based on your most listened music with the ability to filter it</p>
                    </div>

                    <div class="btn-container mt-20">
                        <a class="btn" href="/account">View your account</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fifty-fifty my-60 md:my-90 xl:my-120">
        <div class="site-container">
            <div class="flex flex-col-reverse md:grid md:grid-cols-2 w-full h-full gap-40 xl:gap-80">
                <div class="text-container flex flex-col justify-center md:pt-32 md:pb-60">
                    <h6 class="mb-12 md:mb-20 text-white block px-18 py-8 leading-1 rounded-[20px] bg-[#81d6fd] w-fit uppercase text-9 md:text-11">COMING SOON</h6>

                    <h2 class="md:max-w-[520px]">Apple music</h2>

                    <div class="copy mt-28 md:max-w-[575px]">
                        <p>We are working on the functionality of retaining account information on apple music, this feature will come in the coming months.</p>
                    </div>
                </div>

                <div class="image-container relative h-auto md:h-full aspect-[3/2] md:aspect-auto w-full">
                    <img class="absolute md:relative w-full h-full md:h-auto rounded-[28px]" src="../assets/image-2.jpg" alt="">
                </div>
            </div>
        </div>
    </section>

    <Footer/>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
import axios from 'axios';
import Page from "@/views/layouts/Page";
import Header from "@/views/components/Header";
import Footer from "@/views/components/Footer";
import { trans } from "@/helpers/i18n";
import { useRouter } from 'vue-router';

export default defineComponent({
    components: {
        Page,
        Header,
        Footer
    },
    setup() {
        const router = useRouter();
        const searchText = ref(''); // This ref will hold the search input text

        const navigateToSearch = () => {
            if (searchText.value) {
                router.push({ name: 'Generate Songs', query: { q: searchText.value } });
            } else {
                alert('Please enter a song or an artist');
            }
        };

        const loginWithSpotify = () => {
            // Define your application's client ID and redirect URI
            const clientId = '79fce09ecd854abc88b57c7a72a6f4bd'; 
            const redirectUri = 'http://localhost:8000/callback';
            const scopes = 'user-read-private user-read-email user-top-read';

            // Redirect the user to the Spotify authorization page
            window.location.href = `https://accounts.spotify.com/authorize?client_id=${clientId}&redirect_uri=${encodeURIComponent(redirectUri)}&scope=${encodeURIComponent(scopes)}&response_type=code&show_dialog=true`;
        };

        return {
            trans,
            navigateToSearch,
            searchText,
            loginWithSpotify, // Make this method available to the template
        };
    }
});
</script>

<style scoped>
.homepage-hero {
  background-image: url('../assets/hero-bg.jpg'); /* Adjust the path as necessary */
  background-size: cover;
  background-position: top center;
}
</style>