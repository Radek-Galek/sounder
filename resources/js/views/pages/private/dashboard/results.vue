<template>
    <Header/>

    <section class="similar-songs min-h-[70vh]">
        <div class="site-container filter-container py-100">
            <div class="top-container flex flex-row gap-20">
                <input v-model="promptText" placeholder="Enter song or an artist" class="prompt-input">

                <button class="btn" @click="fetchSimilarSongs"><span><div data-content="Find Similar Songs">Find Similar Songs</div></span></button>
            </div>
            
            <div class="filters mt-20 flex flex-row gap-20">
                <div class="select-container">
                    <select v-model="selectedGenre" class="filter-dropdown">
                        <option disabled value="">Select Genre</option>
                        <option v-for="genre in genres" :key="genre" :value="genre">{{ genre }}</option>
                    </select>
                </div>

                <div class="select-container">
                    <select v-model="selectedMood" class="filter-dropdown">
                        <option disabled value="">Select Mood</option>
                        <option v-for="mood in moods" :key="mood" :value="mood">{{ mood }}</option>
                    </select>
                </div>

                <div class="select-container">
                    <select v-model="numSuggestions" class="filter-dropdown">
                        <option v-for="number in [5, 10, 15, 20]" :key="number" :value="number">{{ number }}</option>
                    </select>
                </div>
            </div>

            <div v-if="isLoading" class="loader">Loading...</div>
            <div v-else class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-20 mt-80" v-if="songs.length">
                <div v-for="(song, index) in songs" :key="index" class="song h-full flex flex-col">
                    <img :src="song.image" :alt="song.name" class="song-image">
                    <p class="song-name mb-10"><b>{{ song.artist }}</b> - {{ song.name }}</p>
                    <a :href="song.spotifyUrl" target="_blank" class="spotify-link mt-auto">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path fill="#1ED760" d="M19.098 10.638c-3.868-2.297-10.248-2.508-13.941-1.387-.593.18-1.22-.155-1.399-.748-.18-.593.154-1.22.748-1.4 4.239-1.287 11.285-1.038 15.738 1.605.533.317.708 1.005.392 1.538-.316.533-1.005.709-1.538.392zm-.126 3.403c-.272.44-.847.578-1.287.308-3.225-1.982-8.142-2.557-11.958-1.399-.494.15-1.017-.129-1.167-.623-.149-.495.13-1.016.624-1.167 4.358-1.322 9.776-.682 13.48 1.595.44.27.578.847.308 1.286zm-1.469 3.267c-.215.354-.676.465-1.028.249-2.818-1.722-6.365-2.111-10.542-1.157-.402.092-.803-.16-.895-.562-.092-.403.159-.804.562-.896 4.571-1.045 8.492-.595 11.655 1.338.353.215.464.676.248 1.028zm-5.503-17.308c-6.627 0-12 5.373-12 12 0 6.628 5.373 12 12 12 6.628 0 12-5.372 12-12 0-6.627-5.372-12-12-12z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <Footer/>
</template>

<script>
import { defineComponent, ref } from 'vue';
import axios from 'axios';
import Header from "@/views/components/Header";
import Footer from "@/views/components/Footer";
import { useRoute } from 'vue-router';

export default defineComponent({
    components: {
        Header,
        Footer
    },
    setup() {
        const songs = ref([]);
        const route = useRoute();
        const promptText = ref("");
        const selectedGenre = ref("");
        const selectedMood = ref("");
        const numSuggestions = ref(5);
        const isLoading = ref(false);

        promptText.value = route.query.q || '';

        const genres = ref([
            'Rock', 'Pop', 'Jazz', 'Electronic', 'Hip Hop', 
            'Classical', 'Country', 'Reggae', 'Blues', 'Folk', 
            'R&B', 'Soul', 'Metal', 'Punk', 'Funk', 
            'Disco', 'House', 'Techno', 'Trance', 'K-pop'
        ]);

        const moods = ref([
            'Happy', 'Sad', 'Energetic', 'Relaxing', 
            'Angry', 'Chill', 'Romantic', 'Melancholic', 'Uplifting', 'Peaceful', 
            'Dreamy', 'Gloomy', 'Hopeful', 'Nostalgic', 'Serene', 
            'Brooding', 'Lively', 'Tense', 'Mysterious', 'Empowering'
        ]);

        const fetchSimilarSongs = () => {
            if (!promptText.value.trim()) {
                alert('Please enter a song to find similar songs.');
                return;
            }

            isLoading.value = true; // Start loading

            let prompt = `Respond with a plain list of similar songs without any additional text or greeting. Find ${numSuggestions.value || 5} songs similar to '${promptText.value}', these do not have to be by the same artist`;

            if (selectedGenre.value) {
                prompt += ` in the genre of ${selectedGenre.value}`;
            }
            if (selectedMood.value) {
                prompt += ` with a ${selectedMood.value} mood.`;
            }

            axios.post('/api/openai/similar-songs', { prompt: prompt })
                .then(async (response) => {
                    const songNames = response.data.choices[0].message.content.split('\n').filter(line => line.trim() !== '');
                    songs.value = await Promise.all(songNames.map(async (name) => {
                        return fetchSongDetails(name);
                    }));
                })
                .catch((error) => {
                    console.error('Error fetching similar songs:', error);
                })
                .finally(() => {
                    isLoading.value = false; // End loading
                });
        };

        const fetchSongDetails = async (songName) => {
            try {
                const response = await axios.post('/api/spotify/find-song', { songName: songName });
                return response.data;
            } catch (error) {
                console.error('Error fetching song details:', error);
                return {};
            }
        };

        return {
            songs,
            promptText,
            fetchSimilarSongs,
            genres,
            moods,
            selectedGenre,
            selectedMood,
            numSuggestions, // Make sure to return the new ref
            isLoading,
        };
    }
});
</script>
