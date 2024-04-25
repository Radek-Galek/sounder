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
            const clientId = '79fce09ecd854abc88b57c7a72a6f4bd'; // Replace with your actual client ID
            const redirectUri = 'http://localhost:8000/callback'; // Replace with your actual redirect URI
            const scopes = 'user-read-private user-read-email user-top-read'; // Define the scopes you need

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
  background-image: url('../../../assets/hero-bg.jpg'); /* Adjust the path as necessary */
  background-size: cover;
  background-position: top center;
}
</style>