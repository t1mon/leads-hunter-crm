<template>
    <div @click="closeHeaderSearchPopupTarget($event)" class="modal fade show" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title font-weight-normal" id="exampleModalLabel">Список ссылок:</h6>
                    <button @click="closeHeaderSearchPopup" type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="modal__ul">
                        <li class="modal__li" v-for="link in links"><a :href="link">{{link}}</a></li>
                    </ul>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "headerSearchPopup",
    data () {
      return {
          links: [
              'LoremIpsumDolorSitAmet',
              'LoremIpsumDolorSitOmet',
              'LoremIpsumDolorSitOmlet',
              'LoremIpsumDolorSitOleg',
              'LoremIpsumDolorSitArbalet',
              'LoremIpsumDolorSitAAAAA'
          ]
      }
    },
    methods: {
        closeHeaderSearchPopup () {
            this.$store.dispatch('closeHeaderSearchPopup')
        },
        closeHeaderSearchPopupTarget (event) {
            this.$store.dispatch('closeHeaderSearchPopupTarget', event)
        }
    }
}
</script>

<style scoped>
.modal {
    background-color: rgba(0,0,0, 0.6);
    z-index: 10000;
    animation: fade-in 0.3s;
}
.dark-version .modal-content {
    background-color: #41485A;
}
.modal__ul {
    list-style-type: none;
}
.modal__li {
    color: #495057 !important;
}
</style>
