var el = '#app';

if (document.querySelector(el)) {


    Vue.options.components['subscribe']

    /*
     * LIKES
     */

    Vue.component('dislike', {
        data: function () {
            return data
        },

        template:
        '   <button v-bind:class="[likeData.normalClass]" class="tooltips btn" id="btn-like" v-on:click="dislike" type="button" data-original-title="Não Gostei"> '+
        '    <i class="fa fa-thumbs-o-down"></i> {{ episodeData.episode.disliked_count }}'+
        '    </button> ',
        methods: {
            dislike: function () {

                if(!loggedInData){
                    app.usersShouldBeLoggedInMsg()
                    return
                }

                //caso seja para cadastrar novo
                if (this.episodeData.episode.liked == undefined){
                    this.episodeData.episode.disliked_count += 1;
                    this.episodeData.episode.liked = false;
                }else{
                    //caso para zerar
                    if (this.episodeData.episode.liked === false){
                        this.episodeData.episode.disliked_count -= 1;
                        this.episodeData.episode.liked = null;
                    }
                    //caso seja para trocar
                    if (this.episodeData.episode.liked === true){
                        this.episodeData.episode.liked_count -= 1;
                        this.episodeData.episode.disliked_count += 1;
                        this.episodeData.episode.liked = false;
                    }
                }


                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                this.$http.post('/api/episode/dislike', {
                    episode_id: this.episodeData.episode.id,
                    liked: this.episodeData.episode.liked
                }, status);

                //console.log(this.episodeData.episode.liked);
                //console.log(this.episodeData.episode.id);

            }
        },
    })

    Vue.component('like', {
        data: function () {
            return data
        },
        template:
        '    <button v-bind:class="[episodeData.episode.liked ? likeData.likedClass : likeData.normalClass]" class="tooltips btn" id="btn-like" v-on:click="like" type="button" data-original-title="Gostei"> '+
        '    <i class="fa fa-thumbs-o-up"></i> {{ episodeData.episode.liked_count }}'+
        '   </button> ',
        methods: {
            like: function () {

                if(!loggedInData){
                    app.usersShouldBeLoggedInMsg()
                    return
                }

                //caso seja para cadastrar novo
                if (this.episodeData.episode.liked == undefined){
                    this.episodeData.episode.liked_count += 1;
                    this.episodeData.episode.liked = true;
                }else{
                    //caso para zerar
                    if (this.episodeData.episode.liked === true){
                        this.episodeData.episode.liked_count -= 1;
                        this.episodeData.episode.liked = null;
                    }
                    //caso seja para trocar
                    if (this.episodeData.episode.liked === false){
                        this.episodeData.episode.disliked_count -= 1;
                        this.episodeData.episode.liked_count += 1;
                        this.episodeData.episode.liked = true;
                    }
                }

                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                this.$http.post('/api/episode/like', {
                    episode_id: episodeData.episode.id,
                    liked: this.episodeData.episode.liked
                }, status);

                //console.log(this.episodeData.episode.liked);
                //console.log(this.episodeData.episode.id);

            }
        }
    })



    /*
     * SUBSCRIPTIONS
     */
    Vue.component('subscribe', {
        data: function () {
            return data
        },
        template:
        '   <button v-if="!programData.program.subscribed" id="btn-subscribe" v-on:click="subscribe"  type="button" class="btn red"> '+
        '    <i class="fa fa-square-o"></i> Assinar '+
        '    </button> ',
        methods: {
            subscribe: function () {

                if(!loggedInData){
                    app.usersShouldBeLoggedInMsg()
                    return
                }

                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                this.$http.post('/api/program/subscribe', {program_id: programData.program.id}, status);
                programData.program.subscribed = true;
                programData.program.subscribed_count += 1;
                //console.log(programData.program.subscribed);
                //console.log(programData.program.id);
                //console.log(programData.program.subscribed_count);
            }
        }
    })

    Vue.component('unsubscribe', {
        data: function () {
            return data
        },
        template:
        '    <button v-if="programData.program.subscribed" id="btn-subscribe" v-on:click="unsubscribe"  type="button" class="btn red"> '+
        '    <i class="fa fa-check-square-o"></i> Inscrito '+
        '   </button> ',
        methods: {
            unsubscribe: function () {

                if(!loggedInData){
                    app.usersShouldBeLoggedInMsg()
                    return
                }

                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                this.$http.post('/api/program/unsubscribe', {program_id: programData.program.id}, status);
                programData.program.subscribed = false;
                programData.program.subscribed_count -= 1;
                //console.log(programData.program.subscribed);
                //console.log(programData.program.id);
                //console.log(programData.program.subscribed_count);
            }
        }
    })

    Vue.component('subscriptions', {
        data: function () {
            return data
        },
        template:
        '   <button type="button" class="btn btn-default"> '+
        '   <i class="fa fa-podcast"></i> {{ programData.program.subscribed_count }}'+
        '   </button>'
    })




    /*
     * PLAYLIST
     */

    Vue.component('playlist', {
        data: function () {
            return data.playlistData
        },
        template: '<div id="playlist"><ul class="task-list">'+
        '<li is="playlist-item" v-for="(episode, index) in playlist" v-bind:episode="episode" v-on:remove="playlist.splice(index, 1)"></li>'+
        '</ul></div>',
    })

    Vue.component('playlist-item', {
        template: '<li>' +
                    '<div class="task-title"><a v-bind:href="url"><span class="task-title-sp">{{ episode.title }}</span></a></div>' +
                    '<div class="task-config"><div class="task-config-btn"><a v-on:click="removeFromPlaylist" class="btn btn-sm default" data-close-others="true"><i class="fa fa-close"></i></a></div></div>' +
                  '</li>',
        props: ['episode'],
        data: function (){
            return data
        },
        computed: {
            url: function () {
                return "/"+this.episode.program.slug+"/"+this.episode.slug;
            }
        },
        methods: {
            removeFromPlaylist: function () {

                if(!loggedInData){
                    app.usersShouldBeLoggedInMsg()
                    return
                }

                //console.log(this.episode.id);
                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                this.$http.post('/api/playlist/remove', {episode_id: this.episode.id}, status);
                this.$emit('remove');
            }
        }
    })



     /*
     * EPISODES-LIST
     * http://jsfiddle.net/crswll/Lr9r2kfv/37/
     */

    Vue.component('episodes-list', {
        data: function () {
            return data
        },
        template: 
        '<div id="episodes">'+
        '<ul class="task-list">'+
        '<li is="episodes-item" v-for="(episode, index) in filteredItems" v-bind:episode="episode"></li>'+
        '</ul></div>',
        computed: {
            filteredItems: function () {
                return this.programData.program.episodes.filter(function(episode){
                    return episode.title.toLowerCase().indexOf(this.searchQuery.toLowerCase()) > -1;
                }.bind(this));
          }
        }

    })

    Vue.component('episodes-item', {
        template: '<li>' +
                    '<div class="task-title"><a v-bind:href="url"><span class="task-title-sp">{{ episode.title }}</span></a></div>' +
                  '</li>',
        props: ['episode'],
        data: function (){
            return data
        },
        computed: {
            url: function () {
                return "/"+programData.program.slug+"/"+this.episode.slug;
            }
        },
        
    })



    /*
     * SUBSCRIPTION MANAGER
     */
    Vue.component('subscription-program', {
        template: '<div>' +
        '    <div class="list-program-logo"> ' +
        '    <img :src="subscription.program.logo.small" alt="logo"> ' +
        '    </div> ' +
        '    <div class="list-program-subscription"> ' +
        '       <button v-on:click="unsubscribe">X</button> ' +
        '    </div> ' +
        '    <div class="list-item-content"> ' +
        '    <h3> ' +
        '    <a :href="subscription.program.slug">{{subscription.program.name}}</a> ' +
        '    </h3>' +
        '    </div>' +
        '    </div>',
        data: function () {
            return data
        },
        props: ['subscription'],
        methods: {
            unsubscribe: function () {
                var subscription = this.subscription;
                subscriptionData.subscriptions = subscriptionData.subscriptions.filter(function (el) {
                    //console.log(el.name);
                    return el !== subscription;
                });
                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                this.$http.post('/api/program/unsubscribe', {program_id: this.subscription.program.id}, status);
            },
        }
    })






    /*
     * APP
     */

    var app = new Vue({
        el: el,
        data: function (){
            return typeof data !== typeof undefined ? data : {};
        },
        methods: {
            addToPlaylist: function (episode) {

                if(!loggedInData){
                    app.usersShouldBeLoggedInMsg()
                    return
                }

                //console.log(episode.id)
                Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');
                this.$http.post('/api/playlist/add', {episode_id: episode.id}, status);
                data.playlistData.playlist.push(episode)
                data.playlisted = !data.playlisted
            },
            usersShouldBeLoggedInMsg: function () {
                window.location.href = '/login'
            }
        }
    })


}

