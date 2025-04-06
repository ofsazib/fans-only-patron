<template>
  <div class="messagefullbodtysec">

    <div class="container">
      <div class="messagefulBox">
        <div class="lechatBox">
          <div class="smshead">
            <div class="slctAdb">
              <input v-model="searchString" :placeholder="searchTranslated" class="form-control" type="text">
            </div>
            <div class="rtSrch">
              <a href="javascript:void(0)">
                <img alt="" src="/images/srchicon.png">
              </a>
            </div>
          </div>
          <div class="smscontLft">
            <div class="sma"><a href="javascript:void(0);">{{ messagesTranslated }}</a></div>
            <div class="chaTlst">
              <ul>
                <li v-for="user in filteredRecipients" :key="'openUser' + user.id"
                    :class="{'current': activeChat == user.id, 'actvsOnline': compareDate(user.updated_at) <= 120}"
                    class="actvs">
                  <div class="media">
                    <i>
                      <a href="javascript:void(0)" @click="openConversation(user.id)">
                        <img :src="'/public/uploads/' + user.profile.profilePic" alt="">
                      </a>
                    </i>
                    <div class="media-body">
                      <h4>
                        <a class="text-333" href="javascript:void(0)" @click="openConversation(user.id)">
                          {{ user.name }}
                        </a>
                      </h4>
                      <p><a class="text-muted" href="javascript:void(0);"
                            @click="openConversation(user.id)">@{{ user.profile.username }}</a></p>
                      <em v-if="user.last_message" :class="{'text-muted': user.last_message.is_read == 'Yes'}">
                        {{ user.last_message.message }}
                      </em>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div><!-- left side -->

        <div :class="{'openactv': activeChat}" class="lechatBoxRt">

          <div class="tabcontains">
            <div class="smshead">
              <div class="slctAdb">
                <div class="media">
                  <i v-if="activeChat">
                    <img :src="'/public/uploads/' + activeRecipient.profile.profilePic" alt="">
                  </i>
                  <div class="media-body">
                    <h4 v-if="activeChat">{{ activeRecipient.name }}</h4>
                    <h4 v-if="!activeChat">{{ openConversationTranslated }}</h4>
                  </div>
                </div>
              </div>
              <div class="rtSrch">
                <a v-if="activeChat" href="javascript:void(0);" @click="closeConversation()">
                  <img alt="" src="/images/close.png">
                </a>
              </div>
            </div><!-- ./right side header -->

            <div v-if="activeChat" v-chat-scroll class="smscontRt">
              <div class="smscontRtBox">
                <div v-for="m in messages" :key="'msg'+m.id" :class="{'fxrt': m.receiver.id != activeChat}"
                     class="chtSMSRw">
                  <div class="media">
                    <i><img :src="'/public/uploads/' + m.sender.profile.profilePic" alt=""></i>
                    <div class="media-body">
                      <strong>{{ m.message }}
                        <message-media :authId="authUser.id" :media="m.media" :msgId="m.id"
                                       :pm-count="authUser.payment_methods_count" :sender="m.sender.id"></message-media>
                      </strong>


                      <p>
                        <i v-if="m.is_read == 'No'" class="fas fa-check-double"></i>
                        <i v-if="m.is_read == 'Yes'" class="fas fa-check-circle"></i>
                        <small>{{ m.timeAgo }}</small>
                      </p>
                    </div> 
                    
                  </div>
                  
                </div>

                <div class="chtSMSRw" v-if="showSpinner">
                  <div class="media">

                    <div class="media-body">
                      <strong><i class="fas fa-spinner fa-spin"></i></strong>
                    </div>
                  </div>
                </div>

              </div>
            </div>


            <div v-if="activeChat">
              <textarea v-model="message" class="sendMessageTextarea ml-2 ml-sm-0 ml-md-0"></textarea>
              <div class="clearfix"></div>

              <!-- only show these if user = creator -->
              <div class="text-right mt-2 mr-2">

                <div v-if="!fileSelectedForUpload">
                <a class="btn text-muted mt-1" href="javascript:void(0);" @click="attach('Image')">
                  <h3 class="d-inline"><i class="fas fa-image"></i></h3>
                </a>

                <a class="mt-1 btn text-muted" href="javascript:void(0);" @click="attach('Video')">
                  <h3 class="d-inline"><i class="fas fa-video"></i></h3>
                </a>

                <a class="mt-1 btn text-muted" href="javascript:void(0);" @click="attach('Audio')">
                  <h3 class="d-inline"><i class="fas fa-music"></i></h3>
                </a>

                <a class="ml-1 mt-1 mr-2 btn text-muted" href="javascript:void(0);" @click="attach('ZIP')">
                  <h3 class="d-inline"><i class="fas fa-file-archive"></i></h3>
                </a>
                </div>

                <div v-if="fileSelectedForUpload && attachments.length > 0" class="pb-2">
                  {{ attachments[0].name }}
                  <a class="text-danger mr-2" href="javascript:void(0)" @click="resetUploadedFile">x</a>
                </div>

                <input ref="imageUploadInput" accept="image/*" class="d-none" type="file" @change="uploadSelected">
                <input ref="videoUploadInput" accept="video/mp4,video/webm,video/ogg,video/quicktime" class="d-none"
                       type="file" @change="uploadSelected">
                <input ref="audioUploadInput" accept="audio/mp3,audio/ogg,audio/wav" class="d-none" type="file"
                       @change="uploadSelected">
                <input ref="zipUploadInput"
                       accept="zip,application/zip,application/x-zip,application/x-zip-compressed,.zip"
                       class="d-none" type="file" @change="uploadSelected">
              </div>

              <div class="text-right mr-2">
                <a class="mr-2" href="javascript:void(0)" @click="switchCost()" v-if="authUser.profile.isVerified === 'Yes' && showPriceInput">
                  {{setFreeTranslated }}
                </a>

                <!-- aici tre sa umblu -->
                <input v-model="price" class="messageCost" type="number" v-if="authUser.profile.isVerified === 'Yes' && showPriceInput">

                <a class="mr-2" href="javascript:void(0)" @click="switchCost()" v-if="authUser.profile.isVerified === 'Yes' && !showPriceInput">
                  {{ setPriceTranslated }}
                </a>

                <a class="btn btn-primary mr-4" href="javascript:void(0);" @click="sendMessage()">
                  <i class="fas fa-paper-plane"></i>
                </a>
              </div>


            </div>

          </div>
        </div><!-- ./tabcontains -->

      </div><!-- right side -->
    </div><!-- ./messageFullBox -->

  </div><!-- ./container -->
  </div>
</template>

<script>

const axios = require('axios');
import MessageMedia from "./MessageMedia";
import Vue from 'vue';

export default {
  components: {
    MessageMedia
  },
  data() {
    return {
      searchTranslated: window.search,
      messagesTranslated: window.messages,
      openConversationTranslated: window.openConversation,
      setPriceTranslated: window.setPrice,
      setFreeTranslated: window.setFree,
      price: 0,
      searchString: "",
      activeChat: null,
      activeRecipient: {},
      recipients: [],
      messages: [],
      message: "",
      serverTime: "",
      reloadInterval: null,
      messageID: null,
      attachmentType: null,
      fileSelectedForUpload: false,
      attachments: [],
      file: null,
      chunks: [],
      uploaded: 0,
      authUser: 0,
      showSpinner: false,
      showPriceInput: false,
    }
  },
  mounted() {
    this.getUser();
    this.getRecipientsForUser();
    this.getServerTime();
  },
  methods: {
    getUser() {
      const vm = this;

      axios.post('/messages/get/auth-user')
          .then(function (response) {

            vm.authUser = response.data;

          })
          .catch(function (error) {
            vm.$toast.error('Fetching User: ' + error.toString());
          });
    },
    switchCost() {
      this.showPriceInput = !this.showPriceInput;
    },
    getRecipientsForUser() {

      const vm = this;

      axios.get('/messages/get/recipients')
          .then(function (response) {

            vm.recipients = response.data;

          })
          .catch(function (error) {
            vm.$toast.error('Fetching Contacts: ' + error.toString());
          });

    },
    openConversation(userID) {

      // reset interval
      // this.closeConversation();
      if(this.reloadInterval) {
        clearInterval(this.reloadInterval);
        this.reloadInterval = null;
      }

      // set active chat
      this.activeChat = userID;

      // set active recipient
      this.activeRecipient = this.recipients[this.recipients.findIndex(x => x.id === userID)];

      // set ref to vue
      const vm = this;

      // get past conversations
      axios.get('/messages/' + userID)
          .then(function (response) {

            vm.messages = response.data;

          })
          .catch(function (error) {
            vm.$toast.error('Fetching messagins: ' + error.toString());
          });

      // create interval
      if (!this.reloadInterval) {
        this.reloadInterval = setInterval(function () {
          vm.openConversation(userID);
        }, 3000);
      }

    },
    sendMessage() {

      const vm = this;

      // show spinner
      vm.showSpinner = true;

      // get past conversations
      axios.post('/messages/send/' + vm.activeChat, {
        'toUserId': vm.activeChat,
        'message': vm.message,
        'price': vm.price
      })
          .then(function (response) {

            // clear message
            vm.message = '';
            vm.messageID = response.data.message.id;

            // do we need to attach anything on this message?
            if (vm.attachments.length > 0) {

              console.log('Starting attachments uploading for message ' + vm.messageID);

              for (let i = 0; i < vm.attachments.length; i++) {

                console.log('Uploading file #' + i);
                console.log(vm.attachments[i]);

                // trigger upload for each item
                vm.createChunks(vm.attachments[i]);

              }
            } else {
              console.log('No attachments for message ' + vm.messageID);
              console.log(vm.attachments);

              // show spinner
              vm.showSpinner = false;
            }


          })
          .catch(function (error) {
            if (typeof error !== 'undefined') {

              if (typeof error.response != 'undefined') {
                let errorMsg = error.response.data.message;

                if (typeof errorMsg !== 'undefined') {
                  vm.$toast.error(errorMsg);
                } else {
                  vm.$toast.error('Error sending message: ' + error.toString());
                }
              } else {
                vm.$toast.error(error.toString());
              }

            } else {
              vm.$toast.error('An error occured while uploading attachments.');
            }
          });
    },
    attach(uploaderRef) {

      // click the input
      if (uploaderRef === 'Image') {
        this.$refs.imageUploadInput.click();
      } else if (uploaderRef === 'Audio') {
        this.$refs.audioUploadInput.click();
      } else if (uploaderRef === 'Video') {
        this.$refs.videoUploadInput.click();
      } else if (uploaderRef === 'ZIP') {
        this.$refs.zipUploadInput.click();
      }

      // set type
      this.attachmentType = uploaderRef;

    },
    uploadSelected(event) {

      const filesNum = event.target.files.length;
      const attachments = event.target.files;
      this.fileSelectedForUpload = true;

      // clean past attachments
      this.attachments = [];

      // append this file
      for (let i = 0; i < filesNum; i++) {
        const file = attachments[i];
        this.attachments.push(file);
      }
    },
    resetUploadedFile() {
      this.attachments = [];
      this.chunks = [];
      this.fileSelectedForUpload = false;
    },
    createChunks(file) {
      let size = 2048 * 1024, chunks = Math.ceil(file.size / size);

      for (let i = 0; i < chunks; i++) {
        this.chunks.push(file.slice(
            i * size, Math.min(i * size + size, file.size), file.type
        ));
      }
    },
    progress(file) {
      return Math.floor((this.uploaded * 100) / file.size);
    },
    uploadChunks() {

      const vm = this;
      const price = vm.price;

      for (let i = 0; i < vm.attachments.length; i++) {

        vm.showSpinner = true;

        // compute form
        let formData = new FormData;

        formData.set('is_last', vm.chunks.length === 1);
        formData.set('file', vm.chunks[0], `${vm.attachments[i].name}.part`);
        formData.set('messageID', vm.messageID);
        formData.set('attachmentType', vm.attachmentType);
        formData.set('price', price);

        axios({
          method: 'POST',
          data: formData,
          url: '/messages/attach-media/' + vm.messageID,
          headers: {
            'Content-Type': 'application/octet-stream'
          },
          onUploadProgress: event => {
            vm.uploaded += event.loaded;
          }
        }).then(response => {

          console.log(response.data);
          vm.chunks.shift();

        }).catch(error => {

          vm.$toast.error('Error occured while uploading attachments:');
          vm.$toast.error(error.toString());
          vm.chunks = [];
          vm.attachments = [];
          vm.showSpinner = false;

        });

        // remove attachment && reset price
        if (vm.chunks.length === 1) {

          vm.attachments.splice(i, 1);
          vm.price = 0;
          vm.fileSelectedForUpload = false;

          // show spinner
          vm.showSpinner = false;

        }

      }

    },
    getServerTime() {
      const vm = this;

      // get past conversations
      axios.get('/messages/get/serverTime')
          .then(function (response) {

            vm.serverTime = response.data.time;

          })
          .catch(function (error) {
            vm.$toast.error('Fetching server time: ' + error.toString());
          });
    },
    closeConversation() {

      clearInterval(this.reloadInterval);

      this.activeChat = null;
      this.messages = [];
      this.activeRecipient = {};
      this.price = 0;
      this.reloadInterval = null;
    },
    compareDate(date) {
      const updated_at = new Date(date);
      const serverTime = new Date(this.serverTime);

      const diff = (serverTime - updated_at) / 1000;

      // console.log(diff)

      return diff;
    },
  },
  computed: {
    filteredRecipients() {
      if (this.searchString) {
        return this.recipients.filter((user) => {
          return this.searchString.toLowerCase().split(' ').every(v => user.profile.name.toLowerCase().includes(v) || user.profile.username.toLowerCase().includes(v))
        })
      } else {
        return this.recipients;
      }
    },
  },
  watch: {
    chunks(n, o) {
      if (n.length > 0) {
        this.uploadChunks();
      }
    }
  },
  beforeDestroy() {
    if (this.reloadInterval) {
      clearInterval(this.reloadInterval);
    }
  }
}
</script>

<style>

</style>
