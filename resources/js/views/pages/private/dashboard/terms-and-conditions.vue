<template>
    <Header/>

    <div class="innerpage-hero py-120 text-center text-blue-300">
        <h1>Terms and conditions</h1>
    </div>

    <div class="copy site-container mb-60 md:mb-80">
        <h4>Introduction</h4>
        <p>Welcome to Sounder! These Terms and Conditions outline the rules and regulations for the use of Sounder's Website, located at <a href="https://sounder.co.uk">sounder.co.uk</a>.</p>
        <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use Sounder if you do not agree to take all of the terms and conditions stated on this page.</p>
        
        <h4>License to Use Website</h4>
        <p>Unless otherwise stated, Sounder and/or its licensors own the intellectual property rights for all material on Sounder. All intellectual property rights are reserved. You may access this from Sounder for your own personal use subjected to restrictions set in these terms and conditions.</p>
        <p>You must not:</p>
        <ul>
            <li>Republish material from Sounder</li>
            <li>Sell, rent or sub-license material from Sounder</li>
            <li>Reproduce, duplicate or copy material from Sounder</li>
            <li>Redistribute content from Sounder</li>
        </ul>

        <h4>Service Description</h4>
        <p>Sounder allows you to connect and manage your Spotify playlists and tracks. Our service requires you to authorize our access to your Spotify account to retrieve, manage, and modify your playlist data.</p>

        <h4>User Accounts</h4>
        <p>When you create an account with us, you must provide information that is accurate, complete, and current at all times. Failure to do so constitutes a breach of the terms, which may result in immediate termination of your account on our service.</p>

        <h4>Privacy</h4>
        <p>Your privacy is important to us. Our Privacy Policy is available at <a href="https://sounder.co.uk/privacy-policy">sounder.co.uk/privacy-policy</a>, and it explains how we collect, use, and share information about you.</p>

        <h4>Termination</h4>
        <p>We may terminate or suspend access to our service immediately, without prior notice or liability, for any reason whatsoever, including, without limitation, if you breach the Terms.</p>

        <h4>Limitation of Liability</h4>
        <p>In no event shall Sounder, nor any of its officers, directors and employees, be held liable for anything arising out of or in any way connected with your use of this website whether such liability is under contract. Sounder, including its officers, directors and employees shall not be held liable for any indirect, consequential or special liability arising out of or in any way related to your use of this website.</p>

        <h4>Indemnification</h4>
        <p>You agree to indemnify and defend Sounder and its officers, directors, employees, and agents against any and/or all claims, costs, demands, causes of action, damages and expenses arising in any way related to your breach of any of the provisions of these terms.</p>

        <h4>Governing Law & Jurisdiction</h4>
        <p>These terms will be governed by and interpreted in accordance with the laws of the United Kingdom, and you submit to the non-exclusive jurisdiction of the state and federal courts located in UK for the resolution of any disputes.</p>

        <h4>Contact Us</h4>
        <p>If you have any questions about these Terms and Conditions, please contact us at support@sounder.co.uk.</p>
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