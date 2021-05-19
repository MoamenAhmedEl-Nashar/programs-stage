<template>

  <v-app>
    <v-carousel
      delimiter-icon="fiber_manual_record"
      next-icon="navigate_next"
      prev-icon="navigate_before"
      cycle
      show-arrows-on-hover
    >
      <v-carousel-item v-for="(program, i) in normalPrograms" :key="i">
        <v-sheet :color="color" height="100%">
          <v-row align="center" justify="center">
            <v-col cols="1"></v-col>
            <v-col cols="2">
              <v-icon large v-if="program.web_or_desktop == 'web'">http</v-icon>
              <v-icon large v-if="program.web_or_desktop == 'desktop'">computer</v-icon>
            </v-col>
            <v-col align="center" cols="6">
              <div class="display-1">{{ program.name }}</div>
            </v-col>
            <v-col cols="3">
              <v-row>
                <v-col cols="6">
                  <v-chip class="ma-2" label>Version {{ program.version }}</v-chip>
                </v-col>
                <v-col cols="6">
                  <v-chip class="ma-2" label>{{ program.database }}</v-chip>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
          <v-row align="center" justify="center">
            <v-chip outlined large>{{ program.description }}</v-chip>
          </v-row>
          <br />
          <v-row align="center" justify="center">
            <div>
              <div v-for="ip in format(program.ip)" :key="ip.label">
                {{ip.label}}
                <v-icon>double_arrow</v-icon>
                <v-btn outlined depressed small :href="'//'+ip.link">{{ip.link}}</v-btn>
              </div>
            </div>
          </v-row>
          <v-row align="center" justify="center">
            <v-list color="teal darken-3">
              <v-list-item v-for="(link, i) in program.links" :key="i">
                <v-row v-if="link.type=='setup'">
                  {{link.name}}
                  <v-icon>play_arrow</v-icon>
                  <v-btn small @click="download(link.id, link.name)">download</v-btn>
                </v-row>
              </v-list-item>
            </v-list>
          </v-row>
        </v-sheet>
      </v-carousel-item>
    </v-carousel>
  </v-app>
</template>

<style scoped>
</style>

<script>
import { mapGetters } from "vuex";


export default {
  name: "Programs",

  computed: {
    ...mapGetters(["allPrograms"]),
    normalPrograms: function () {
      return this.allPrograms.filter((program) => {
        return program.permission == "normal";
      });
    },
  },

  mounted() {
    this.$store.dispatch("reloadPrograms");
  },
  data() {
    return {
      color: "teal darken-3",
    };
  },

  methods: {
    download: function (id, linkName) {
      fetch(`http://128.16.7.38:8022/api/links/download/${id}`, {
          headers: {
            responseType: "blob",
          },
        })
        .then(response => response.blob())
          .then(blob => {
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = linkName
            document.body.appendChild(a); // we need to append the element to the dom -> otherwise it will not work in firefox
            a.click();    
            a.remove();  //afterwards we remove the element again         
        })
          
    },
    format: function (ipText) {
      var ips = [];
      if (ipText != null) ips = ipText.split(",");

      var ips_array = [];
      for (var i = 0; i < ips.length; i++) {
        var label, link;
        if (ips[i].includes("->")) {
          label = ips[i].split("->")[0];
          link = ips[i].split("->")[1].replace(/\s/g, "");
        } else {
          label = "IP";
          link = ips[i].replace(/\s/g, "");
        }

        var ip = { label: label, link: link };
        ips_array.push(ip);
      }
      return ips_array;
    },
  },
};
</script>