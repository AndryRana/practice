<template>
    <div>
        <button class=" bg-green-500 px-2 py-2 text-white rounded" @click="toggleProgress()">{{ this.isWatched ? 'Terminé' : 'Commencé ?'}}</button>
    </div> 
</template>

<script>
export default {
    props: ['episodeId', 'watchedEpisodes'],

    data() {
        return {
           watchedEp: this.watchedEpisodes,
           isWatched: null
        }
    },

    methods: {
        toggleProgress(){
            axios.post('/toggleProgress', {
                episodeId: this.episodeId,
            })
            .then(response => {
                if(response.status === 200) {
                    this.isWatched = !this.isWatched;
                }
            })
            .catch(error => console.log(error));
        },

        isWatchedEpisode() {
            return this.watchedEp.find(episode => episode.id === this.episodeId) ? true : false;
        }
    },

    mounted() {
       this.isWatched = this.isWatchedEpisode();
    }
}
</script>