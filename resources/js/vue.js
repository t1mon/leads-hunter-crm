//import CommentForm from './components/comments/CommentForm'
//import CommentList from './components/comments/CommentList'
//import Like from './components/Like'
//import Vue from 'vue'
import { createApp } from 'vue'
import CommentForm from './components/comments/CommentForm.vue'

//Vue.config.productionTip = false

//window.Event = new Vue()

const app = createApp({


  mounted() {
    $('[data-confirm]').on('click', () => {
      return confirm($(this).data('confirm'))
    })
  }
}).mount("#app")

app.component('comments-form', CommentForm)
