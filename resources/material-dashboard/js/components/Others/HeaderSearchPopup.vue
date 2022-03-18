<template>
   <div class="headerSearchPopup">
       <span @click="closeHeaderSearchPopup" class="headerSearchPopup__close material-icons">close</span>
       <p class="headerSearchPopup__title">Ссылки:</p>
       <div class="headerSearchPopup__content">
           <ul ref="ul" class="headerSearchPopup__ul">
               <li v-if="stateSearchData" v-for="(link) in stateSearchData.Host" :key="link" class="headerSearchPopup__li">
                   <a :href="'/project/' + link.project_id + '/settings_basic'"><span :title="link">{{ link.host }}</span></a>
               </li>
           </ul>
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
              'LoremIpsumDolorSitArbaletasdasdasdasdadad',
              'LoremIpsumDolorSitArbalet',
              'LoremIpsumDolorSitArbalet',
              'LoremIpsumDolorSitArbalet',
              'LoremIpsumDolorSitArbalet',
              'LoremIpsumDolorSitAAAAA'
          ]
      }
    },
    methods: {
        closeHeaderSearchPopup () {
            this.$store.dispatch('closeHeaderSearchPopup')
        }
    },
    computed: {
        stateHeaderSearchPopup () {
           return this.$store.getters.stateHeaderSearchPopup
        },
        stateSearchData () {
            return this.$store.getters.stateSearchData
        }
    }
}
</script>

<style scoped>
.dark-version .headerSearchPopup {
    background-color: #202940;
}
.headerSearchPopup {
    position: absolute;
    top: 100%;
    left: -1px;
    z-index: 201;
    width: calc(100% + 1px);
    background-color: #FCFCFE;
    border: 1px solid #d2d6da;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem !important;
    overflow: hidden;
    animation: fade-in 0.3s;
}
.headerSearchPopup__content {
    overflow-y: auto;
    max-height: 200px;
}
.headerSearchPopup__close {
    position: absolute;
    top: 10px;
    right: 12px;
    cursor: pointer;
    z-index: 2;
}
.headerSearchPopup__title {
    margin-bottom: 10px;
    padding: 10px 12px;
    position: relative;
}
.headerSearchPopup__title::after {
    content: '';
    position: absolute;
    width: calc(100% - 24px);
    height: 1px;
    background-color: #d2d6da;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}
.headerSearchPopup__ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    padding: 0 12px 12px;
}
.headerSearchPopup__li span:hover {
    color: #e91e63 !important;
}
.headerSearchPopup__li {
    color: #495057 !important;
    font-size: 14px;
    margin-bottom: 5px;
}
.headerSearchPopup__li:last-of-type {
    margin-bottom: 0;
}
.headerSearchPopup__li a {
    display: block;
}
.headerSearchPopup__li span {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
