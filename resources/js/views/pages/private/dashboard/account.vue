<template>
    <Header/>

    <Page>
        <div class="site-container">
            <div v-if="accountDetails" class="account-info my-80">
                <div class="top-container flex flex-row items-center gap-24 mb-30">
                    <img v-if="accountDetails.images && accountDetails.images.length > 0" 
                        :src="accountDetails.images[0].url" 
                        alt="Account Image" 
                        class="account-image rounded-full w-72 h-auto aspect-square">
                    <h2>{{ accountDetails.display_name }}'s Account</h2>
                </div>
                
                <p><b>Email:</b> {{ accountDetails.email }}</p>
                <p><b>Country:</b> {{ accountDetails.country }}</p>
                <!-- Add more account detail elements here -->
            </div>
            <div v-else class="loading">
                Fetching account details...
            </div>

            <div v-if="mostListenedTracks.length > 0" class="tracks-list">
                <h2>Top Tracks</h2>
                <ul class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-20 mt-40" >
                    <li v-for="(track, index) in mostListenedTracks" :key="index">
                        <img :src="track.image" :alt="track.name" class="song-image">
                        <p class="song-name mb-10"><b>{{ track.artist }}</b> - {{ track.name }}</p>
                        <a :href="track.spotifyUrl" target="_blank" class="spotify-link mt-auto">
                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path fill="#1ED760" d="M19.098 10.638c-3.868-2.297-10.248-2.508-13.941-1.387-.593.18-1.22-.155-1.399-.748-.18-.593.154-1.22.748-1.4 4.239-1.287 11.285-1.038 15.738 1.605.533.317.708 1.005.392 1.538-.316.533-1.005.709-1.538.392zm-.126 3.403c-.272.44-.847.578-1.287.308-3.225-1.982-8.142-2.557-11.958-1.399-.494.15-1.017-.129-1.167-.623-.149-.495.13-1.016.624-1.167 4.358-1.322 9.776-.682 13.48 1.595.44.27.578.847.308 1.286zm-1.469 3.267c-.215.354-.676.465-1.028.249-2.818-1.722-6.365-2.111-10.542-1.157-.402.092-.803-.16-.895-.562-.092-.403.159-.804.562-.896 4.571-1.045 8.492-.595 11.655 1.338.353.215.464.676.248 1.028zm-5.503-17.308c-6.627 0-12 5.373-12 12 0 6.628 5.373 12 12 12 6.628 0 12-5.372 12-12 0-6.627-5.372-12-12-12z"/></svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div v-else class="loading">
                Fetching top tracks...
            </div>

            <div class="generate-playlist mt-80">
                <h3>Generate a playlist based on most listened songs</h3>

                <div class="filters filter-container mt-20 flex flex-row gap-20">
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
                            <option v-for="number in [15, 25, 30, 35]" :key="number" :value="number">{{ number }}</option>
                        </select>
                    </div>

                    <button @click="fetchSimilarSongs" class="btn"><span><div data-content="Find Similar Songs">Find Similar Songs</div></span></button>
                </div>
            </div>

            <div v-if="isLoading" class="loader mt-80">Loading...</div>
            <div v-else class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-20 mt-80">
                <div v-for="(song, index) in songs" :key="index" class="song h-full flex flex-col">
                    <img :src="song.image" :alt="song.name" class="song-image">
                    <p class="song-name mb-10"><b>{{ song.artist }}</b> - {{ song.name }}</p>
                    <a :href="song.spotifyUrl" target="_blank" class="spotify-link mt-auto">
                        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path fill="#1ED760" d="M19.098 10.638c-3.868-2.297-10.248-2.508-13.941-1.387-.593.18-1.22-.155-1.399-.748-.18-.593.154-1.22.748-1.4 4.239-1.287 11.285-1.038 15.738 1.605.533.317.708 1.005.392 1.538-.316.533-1.005.709-1.538.392zm-.126 3.403c-.272.44-.847.578-1.287.308-3.225-1.982-8.142-2.557-11.958-1.399-.494.15-1.017-.129-1.167-.623-.149-.495.13-1.016.624-1.167 4.358-1.322 9.776-.682 13.48 1.595.44.27.578.847.308 1.286zm-1.469 3.267c-.215.354-.676.465-1.028.249-2.818-1.722-6.365-2.111-10.542-1.157-.402.092-.803-.16-.895-.562-.092-.403.159-.804.562-.896 4.571-1.045 8.492-.595 11.655 1.338.353.215.464.676.248 1.028zm-5.503-17.308c-6.627 0-12 5.373-12 12 0 6.628 5.373 12 12 12 6.628 0 12-5.372 12-12 0-6.627-5.372-12-12-12z"/></svg>
                    </a>
                    <button @click="replaceSong(index)" class="replace-btn btn !w-full mt-10">Replace Song</button>
                </div>
            </div>

            <!-- Button to Trigger Playlist Creation -->
            <div class="create-playlist my-80 flex items-center justify-center" v-if="songs.length">
                <button @click="openModal" class="btn">Create Playlist</button>
            </div>
        </div>

        <div v-if="showCreatePlaylistModal" class="modal">
            <div class="modal-content">
                <span class="close" @click="closeModal()">&times;</span>
                <h4 class="mb-30">Enter Playlist Name</h4>
                <input type="text" v-model="newPlaylistName" placeholder="Playlist Name">

                <div class="buttons-container flex flex-row w-full gap-15 mt-15">
                    <button class="btn flex-1" @click="createPlaylist">Create Playlist</button>
                    <button class="btn flex-1" @click="closeModal">Cancel</button>
                </div>
            </div>
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

export default defineComponent({
    components: {
        Page,
        Header,
        Footer
    },
    setup() {
        const accountDetails = ref(null);
        const mostListenedTracks = ref([]);
        const songs = ref([]);
        const selectedGenre = ref("");
        const selectedMood = ref("");
        const numSuggestions = ref(15);
        const showCreatePlaylistModal = ref(false);
        const newPlaylistName = ref('');
        const isLoading = ref(false);

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

        const fetchAccountDetails = async () => {
            try {
                const response = await axios.get('/api/spotify/account-details'); // Replace with your actual API endpoint
                accountDetails.value = response.data;
            } catch (error) {
                console.error('There was an error fetching the account details:', error);
                // Define your application's client ID and redirect URI
                const clientId = '79fce09ecd854abc88b57c7a72a6f4bd'; // Replace with your actual client ID
                const redirectUri = 'http://localhost:8000/callback'; // Replace with your actual redirect URI
                const scopes = 'user-read-private user-read-email user-top-read'; // Define the scopes you need

                // Redirect the user to the Spotify authorization page
                window.location.href = `https://accounts.spotify.com/authorize?client_id=${clientId}&redirect_uri=${encodeURIComponent(redirectUri)}&scope=${encodeURIComponent(scopes)}&response_type=code&show_dialog=true`;
            }
        };

        const fetchMostListenedTracks = async () => {
            try {
                // Assuming your API call logic...
                const response = await axios.get('/api/spotify/most-listened-tracks');
                console.log(response.data);
                mostListenedTracks.value = response.data.map(track => ({
                    image: track.album.images[0].url,
                    name: track.name,
                    artist: track.artists.map(artist => artist.name).join(', '), // Assuming you want to join multiple artist names
                    spotifyUrl: track.external_urls.spotify
                }));
            } catch (error) {
                console.error('There was an error fetching the most listened tracks:', error);
                mostListenedTracks.value = []; // Ensure it's set to an empty array in case of an error
            }
        };

        const fetchSimilarSongs = async () => {
            isLoading.value = true;

            // Check if there are most listened tracks to base our recommendations on
            if (mostListenedTracks.value.length === 0) {
                alert('No most listened tracks available to find similar songs.');
                return;
            }

            // Generate a prompt describing the most listened tracks
            let prompt = `Respond with a plain list of similar songs without any additional text or greeting. Based on the following tracks: `;
            mostListenedTracks.value.slice(0, 5).forEach((track, index) => {
                prompt += `'${track.name} by ${track.artist}'${index < mostListenedTracks.value.length - 1 ? ', ' : ''}`;
            });

            prompt += `, find ${numSuggestions.value || 5} similar songs`;

            if (selectedGenre.value) {
                prompt += ` in the genre of ${selectedGenre.value}`;
            }
            if (selectedMood.value) {
                prompt += ` with a ${selectedMood.value} mood.`;
            }

            // Make an API request to get similar song recommendations
            try {
                const response = await axios.post('/api/openai/similar-songs', { prompt: prompt });

                const songNames = response.data.choices[0].message.content.split('\n').filter(line => line.trim() !== '');
                songs.value = await Promise.all(songNames.map(async (name) => {
                    // Assuming fetchSongDetails is another function that makes a request to your backend
                    // and that the backend correctly handles the request to return the structured data including
                    // the artist name, Spotify URL, and song preview URL.
                    const response = await axios.post('/api/spotify/find-song', { songName: name });
                    const songDetails = response.data;

                    return {
                        name: songDetails.name || name, // Use the name from the details or the original name
                        artist: songDetails.artist || 'Unknown Artist', // Default to 'Unknown Artist' if not found
                        image: songDetails.image || 'default-image-url.jpg', // Default image if not found
                        spotifyUrl: songDetails.spotifyUrl, // The URL to the song on Spotify
                        previewUrl: songDetails.previewUrl // The URL to preview the song
                    };
                }));
            } catch (error) {
                console.error('Error fetching similar songs:', error);
            }finally {
                isLoading.value = false;
            }
        };

        const replaceSong = async (index) => {
            const currentTrack = songs.value[index];

            // Check if there are most listened tracks to base our recommendations on
            if (mostListenedTracks.value.length === 0) {
                alert('No most listened tracks available to find similar songs.');
                return;
            }

            // Generate a prompt describing the most listened tracks
            let prompt = `Respond with a single song that is not listed in the following sentence, Based on the following tracks: `;
            mostListenedTracks.value.slice(0, 5).forEach((track, index) => {
                prompt += `'${track.name} by ${track.artist}'${index < mostListenedTracks.value.length - 1 ? ', ' : ''}`;
            });

            // Adding a condition to avoid current songs in the playlist
            const existingSongNames = songs.value.map(song => `'${song.name} by ${song.artist}'`).join(', ');
            prompt += `, find only 1 similar song that is not one of these songs and is not a variation of one of these songs: ${existingSongNames}`;

            if (selectedGenre.value) {
                prompt += ` in the genre of ${selectedGenre.value}`;
            }
            if (selectedMood.value) {
                prompt += ` with a ${selectedMood.value} mood.`;
            }

            try {
                const response = await axios.post('/api/openai/similar-songs', { prompt });
                const songName = response.data.choices[0].message.content.trim();
                console.log(songName);

                const songDetailsResponse = await axios.post('/api/spotify/find-song', { songName });
                const newSongDetails = songDetailsResponse.data;

                // Update only the song at the specific index
                songs.value[index] = {
                    name: newSongDetails.name,
                    artist: newSongDetails.artist,
                    image: newSongDetails.image || 'default-image-url.jpg',
                    spotifyUrl: newSongDetails.spotifyUrl,
                    previewUrl: newSongDetails.previewUrl
                };
            } catch (error) {
                console.error('Error replacing the song:', error);
            }
        };

        const openModal = () => {
            showCreatePlaylistModal.value = true;
        };

        const closeModal = () => {
            showCreatePlaylistModal.value = false;
            newPlaylistName.value = ''; // Reset the input field
        };

        const createPlaylist = async () => {
            if (!newPlaylistName.value.trim()) {
                alert('Please enter a playlist name');
                return;
            }

            try {
                const playlistDetails = {
                    name: newPlaylistName.value,
                    description: "Playlist created based on your most listened tracks",
                    tracks: songs.value.map(song => song.spotifyUrl)
                };

                const response = await axios.post('/api/spotify/create-playlist', playlistDetails);
                alert('Playlist created successfully!');
                closeModal();
            } catch (error) {
                console.error('Error creating playlist:', error);
                alert('Failed to create playlist.');
            }
        };


        const init = async () => {
            await fetchAccountDetails();
            await fetchMostListenedTracks();
        };

        onMounted(init);

        return {
            accountDetails,
            mostListenedTracks,
            genres,
            moods,
            selectedGenre,
            selectedMood,
            numSuggestions, // Make sure to return the new ref
            fetchSimilarSongs,
            replaceSong,
            createPlaylist,
            songs,
            showCreatePlaylistModal,
            newPlaylistName,
            openModal,
            closeModal,
            isLoading,
        };
    }
});
</script>