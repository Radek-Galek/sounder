<template>
    <Header/>

    <Page>
        <div v-if="mostListenedTrack" class="track-info">
            <h1>Most Listened Song</h1>
            <p>Track Name: {{ mostListenedTrack.name }}</p>
            <p>Artist(s): {{ mostListenedTrack.artists.join(', ') }}</p>
            <!-- Add more details here -->
        </div>
        <div v-else class="loading">
            Fetching your most listened song...
        </div>
    </Page>

    <Footer/>
</template>

<script>
import { defineComponent, ref, onMounted } from 'vue';
import axios from 'axios';
import Page from "@/views/layouts/Page";
import Header from "@/views/components/Header";
import Footer from "@/views/components/Footer";
import { useRouter } from 'vue-router';

export default defineComponent({
    components: {
        Page,
        Header,
        Footer
    },
    setup() {
        const mostListenedTrack = ref(null);
        const router = useRouter();
        

        const fetchAccessToken = async (code) => {
            try {
                const response = await axios.post('/api/spotify/token', { code: code });
                return response.data.access_token; // Adjust according to the actual response structure
            } catch (error) {
                console.error('There was an error fetching the access token:', error);
                return null;
            }
        };


        const fetchMostListenedTrack = async (accessToken) => {
            try {
                const response = await axios.get('/api/spotify/most-listened', {
                    headers: { 'Authorization': `Bearer ${accessToken}` }
                });
                mostListenedTrack.value = response.data; // Adjust based on the actual response structure
            } catch (error) {
                console.error('There was an error fetching the most listened track:', error);
            }
        };

        const init = async () => {
            const urlParams = new URLSearchParams(window.location.search);
            const code = urlParams.get('code');

            if (code) {
                const accessToken = await fetchAccessToken(code);
                if (accessToken) {
                    // If you have the access token, presumably the user is authenticated,
                    // so you can redirect to the account page.
                    router.push({ name: 'Account' }); // Replace 'Account' with the actual route name for the account page.
                }
            } else {
                // If there is no code in the URL, redirect to the account page as well.
                router.push({ name: 'Account' }); // Replace 'Account' with the actual route name for the account page.
            }
        };

        onMounted(init);

        return {
            mostListenedTrack,
            router
        };
    }
});
</script>
