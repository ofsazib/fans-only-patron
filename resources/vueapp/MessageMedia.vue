<template>
  <div>
    <div v-if="media.length" class="mt-2">
      <div v-if="canView">
        <!-- image tag -->

        <img
          v-img="{ src: m.media_content }"
          :src="m.media_content"
          class="img-fluid"
          v-if="m.media_type === 'Image'"
        />

        <!-- video tag -->
        <!--        <div class="embed-responsive embed-responsive-16by9 m-2" v-if="m.media_type === 'Video'">-->
        <video
          controls
          :controlsList="download"
          v-if="m.media_type === 'Video'"
          preload="metadata"
          disablePictureInPicture
        >
          <source :src="m.media_content + '#t=0.5'" type="video/mp4" />
        </video>
        <!--        </div>-->

        <!-- audio tag -->
        <audio
          class="w-100 p-2"
          style="min-width: 300px;"
          :controls="download"
          :controlsList="download"
          v-if="m.media_type === 'Audio'"
        >
          <source :src="m.media_content" type="audio/mp3" />
        </audio>

        <!-- zip tag -->
        <a
          :href="'/messages/download-zip/' + m.id"
          target="_blank"
          class="ml-1 mt-2 btn btn-primary btn-sm"
          v-if="m.media_type === 'ZIP'"
        >
          <i class="fas fa-file-archive"></i> {{ zipDownload }}
        </a>
      </div>

      <div v-else>
        <message-unlock-button
          :price="m.lock_price"
          :message-id="msgId"
          :pm-count="pmCount"
        ></message-unlock-button>
      </div>
    </div>
  </div>
</template>

<script>
import MessageUnlockButton from "./MessageUnlockButton";

export default {
  components: {
    MessageUnlockButton,
  },
  props: {
    media: [Object, Array],
    sender: Number,
    authId: Number,
    pmCount: Number,
    msgId: Number,
  },
  data: function() {
    return {
      m: {},
      enableMediaDl: window.enableMediaDl,
      zipDownload: window.zipDownload,
    };
  },
  mounted() {
    if (this.media.length > 0) {
      this.m = this.media[0];
    }
  },
  computed: {
    canView() {
      let m = this.m;

      if (m.lock_type == "Paid" && m.lock_price > 0) {
        if (this.authId != this.sender) {
          return false;
        } else {
          return true;
        }
      } else {
        return true;
      }
    },
    download() {
      if (this.enableMediaDl == "No") {
        return "nodownload";
      } else {
        return "";
      }
    },
  },
};
</script>

<style scoped>
video {
  width: 100%;
}
</style>
