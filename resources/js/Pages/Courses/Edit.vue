<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Modification de  {{ courseData.title }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-green-200 text-green-500 p-3" v-if="$page.flash.success">
          {{ $page.flash.success }}
        </div>
        <form
          class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"
          @submit.prevent="submit"
        >
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="title"
            >
              Titre de la formation
            </label>
            <input
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="title"
              type="text"
              v-model="courseData.title"
            />
            <div class="bg-red-200 text-red-800 p-4 my-2" v-if="$page.errors.title">{{$page.errors.title[0]}}</div>
          </div>
          <div class="mb-4">
            <label
              class="block text-gray-700 text-sm font-bold mb-2"
              for="description"
            >
              Description de la formation
            </label>
            <textarea
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              id="title"
              type="text"
              v-model="courseData.description"
            >
            </textarea>
            <div class="bg-red-200 text-red-800 p-4 my-2" v-if="$page.errors.description">{{$page.errors.description[0]}}</div>
          </div>
          <div class="mb-4">
            <h2 class="text-2xl">Episodes de la formation</h2>
            <div v-for="(episodes, index) in courseData.episodes" v-bind:key="index">
              <label
                class="block text-gray-700 text-sm font-bold mb-2"
                :for="'title-'+index"
              >
                Titre de l'épisode n° {{ index + 1 }}
              </label>
              <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                :id="'title-'+index"
                type="text"
                v-model="courseData.episodes[index].title"
              />

              <div class="bg-red-200 text-red-800 p-4 my-2" v-if="$page.errors['episodes.' + index + '.title']">{{$page.errors['episodes.' + index + '.title'][0]}}</div>

              <label
                class="block text-gray-700 text-sm font-bold mb-2"
                :for="'description-'+index"
              >
                Description de l'épisode n° {{ index + 1 }}
              </label>
              <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                :id="'description-'+index"
                type="text"
                v-model="courseData.episodes[index].description"
              />

                 <div class="bg-red-200 text-red-800 p-4 my-2" v-if="$page.errors['episodes.' + index + '.description']">{{$page.errors['episodes.' + index + '.description'][0]}}</div>

              <label
                class="block text-gray-700 text-sm font-bold mb-2"
                :for="'episode-video_url-'+index"
              >
                URL de la vidéo de l'épisode n° {{ index + 1 }}
              </label>
              <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-5"
                :id="'episode-video_url-'+index"
                type="text"
                v-model="courseData.episodes[index].video_url"
              />
               <div class="bg-red-200 text-red-800 p-4 my-2" v-if="$page.errors['episodes.' + index + '.video_url']">{{$page.errors['episodes.' + index + '.video_url'][0]}}</div>
            </div>
          </div>
          
          <button class=" bg-green-600 rounded py-2 px-4 my-2 text-white block " v-if="courseData.episodes.length < 15" @click.prevent="add">+</button>
          <button class=" bg-red-600 rounded py-2 px-4 my-2 text-white block " v-if="courseData.episodes.length > 1" @click.prevent="remove">🗑️</button>
          <button
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            type="submit"
          >
            Modifier ma formation
          </button>
        </form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
export default {
    components: {
        AppLayout,
  },

  props:['course'],

  data() {
      return {
          courseData: this.course,
    };
  },


  methods: {
      
      submit() {
          this.$inertia.patch('/courses/' + this.courseData.id, this.courseData);
    },

    add() {
        this.courseData.episodes.push({ title: null, description: null, video_url: null });
    },

    remove() {
        this.courseData.episodes.pop();
    }
  },
};
</script>
