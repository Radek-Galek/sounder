<template>
    <Header/>

    <div class="innerpage-hero py-120 text-center text-blue-300">
        <h1>Privacy Policy</h1>
    </div>

    <div class="copy site-container mb-60 md:mb-80">
        <h4>Introduction</h4>
            <p>Welcome to <strong>Sounder</strong>! Our service allows you to manage your Spotify playlists and listen to your favorite tracks more effectively. This Privacy Policy outlines how we use and protect any information that Spotify provides to us when you use our service. Please read this policy carefully to understand our practices regarding your information and how we will treat it.</p>

            <h4>Information We Access</h4>
            <p>As part of our service, we require access to your Spotify account. Upon your authorization, we can access the following information:</p>
            <ul>
                <li>Your public profile, which includes your username, profile picture, and other public information.</li>
                <li>Your playlists and other Spotify data necessary to manage these playlists.</li>
            </ul>

            <h4>How We Use Your Information</h4>
            <p>The information we access from your Spotify account is used to:</p>
            <ul>
                <li>Display your playlists and track lists on our service.</li>
                <li>Allow you to modify and manage your playlists directly through our application.</li>
                <li>Improve and customize your experience based on your interaction with our service.</li>
            </ul>

            <h4>Sharing Your Information</h4>
            <p>We do not share your information with any third parties except:</p>
            <ul>
                <li>To comply with any applicable law, regulation, legal process, or governmental request.</li>
                <li>To enforce our rights arising from any contracts entered into between you and us, including for billing and collection.</li>
                <li>If we believe disclosure is necessary or appropriate to protect the rights, property, or safety of our company, our customers, or others.</li>
            </ul>

            <h4>Security</h4>
            <p>We are committed to ensuring that your information is secure. In order to prevent unauthorized access or disclosure, we have put in place suitable physical, electronic, and managerial procedures to safeguard and secure the information we access from Spotify.</p>

            <h4>Changes to Our Privacy Policy</h4>
            <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page. You are advised to review this privacy policy periodically for any changes.</p>

            <h4>Contact Us</h4>
            <p>If you have any questions about this privacy policy, please contact us at <strong>contact@sounder.co.uk</strong>.</p>

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
        Header,
        Footer
    },
    setup() {
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