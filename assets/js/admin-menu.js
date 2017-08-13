(function ($) {

    var adminMenu = function () {

        // var CptModel = wp.api.models.Post.extend({
        //     urlRoot: wpApiSettings.root + wpApiSettings.versionString + 'bbcpt'
        // });


        // var CptCollection = wp.api.collections.Posts.extend({
        //     url: wpApiSettings.root + wpApiSettings.versionString + 'bbcpt',
        //     model: CptModel
        // });

        var postCollection = new wp.api.collections.Posts();

        var pageCollection = new wp.api.collections.Pages();

        // 중요: rest_base 가 'bbcpt'이므로 이를 camelCase 형태로 만든 'Bbcpt'가 이미 만들어져 있다.
        var cptCollection = new wp.api.collections.Bbcpt();

        var commonRender = function ($el, template, models) {
            $el.html(template(models));
        };

        var PostView = Backbone.View.extend({
            el: 'article#your-posts > table > tbody',
            initialize: function () {
                this.listenTo(postCollection, 'sync', this.render);
                this.listenTo(postCollection, 'remove', this.render);
                this.listenTo(postCollection, 'add', this.render);
                this.listenTo(postCollection, 'change', this.render);
            },
            tableTemplate: wp.template('table-template'),
            render: function () {
                commonRender(this.$el, this.tableTemplate, postCollection.models);
            }
        });

        var PageView = Backbone.View.extend({
            el: 'article#your-pages > table > tbody',
            initialize: function () {
                this.listenTo(pageCollection, 'sync', this.render);
                this.listenTo(pageCollection, 'remove', this.render);
                this.listenTo(pageCollection, 'add', this.render);
                this.listenTo(pageCollection, 'change', this.render);
            },
            tableTemplate: wp.template('table-template'),
            render: function () {
                commonRender(this.$el, this.tableTemplate, pageCollection.models);
            }
        });

        var CptView = Backbone.View.extend({
            el: 'article#your-custom-posts > table > tbody',
            initialize: function () {
                this.listenTo(cptCollection, 'sync', this.render);
                this.listenTo(cptCollection, 'remove', this.render);
                this.listenTo(cptCollection, 'add', this.render);
                this.listenTo(cptCollection, 'change', this.render);
            },
            tableTemplate: wp.template('table-template'),
            render: function () {
                commonRender(this.$el, this.tableTemplate, cptCollection.models);
            }
        });

        var ControlView = Backbone.View.extend({
            el: 'div#bbcpt-control',

            events: {
                'change select#bbcpt-edit-type': 'render',
                'change select#bbcpt-edit-target': 'render',
                'click input[name="bbcpt-edit-mode"]': 'render',
                'click button#bbcpt-edit-button': 'buttonSubmitClicked'
            },

            targetSelect: $('select#bbcpt-edit-target'),

            buttonSubmitClicked: function () {
                var that = this, collection,
                    editType = $('#bbcpt-edit-type').val(),
                    mode = $('input[name="bbcpt-edit-mode"]:checked', this.$el).data('mode'),
                    editTarget = parseInt($('select#bbcpt-edit-target').val()),
                    model;

                switch (editType) {
                    case 'post':
                        collection = postCollection;
                        break;
                    case 'page':
                        collection = pageCollection;
                        break;
                    case 'bbcpt':
                        collection = cptCollection;
                        break;
                }

                switch (mode) {
                    case 'add':
                        collection.create({
                            title: $('input#bbcpt-edit-title').val(),
                            author: $('select#bbcpt-edit-author').val(),
                            content: $('textarea#bbcpt-edit-content').val(),
                            type: editType,
                            status: 'publish'
                        });
                        break;

                    case 'modify':
                        model = collection.findWhere({'id': editTarget});
                        if (model) {
                            model.save({
                                title: $('input#bbcpt-edit-title').val(),
                                author: $('select#bbcpt-edit-author').val(),
                                content: $('textarea#bbcpt-edit-content').val(),
                                type: editType,
                                status: 'publish'
                            });
                        }
                        break;

                    case 'delete':
                        model = collection.findWhere({id: editTarget});
                        if(model) {
                            var removed = collection.remove(model);
                            removed.destroy();
                        }
                        break;
                }
            },

            render: function (e) {
                var collection,
                    editType = $('#bbcpt-edit-type').val(),
                    mode = $('input[name="bbcpt-edit-mode"]:checked', this.$el).data('mode');

                switch (editType) {
                    case 'post':
                        collection = postCollection;
                        break;
                    case 'page':
                        collection = pageCollection;
                        break;
                    case 'bbcpt':
                        collection = cptCollection;
                        break;
                }

                if (e && e.target.id !== this.targetSelect.attr('id')) {
                    this.targetSelect.html(_.map(collection.models, function (model) {
                        return '<option value="' + model.attributes.id + '">' + model.attributes.title.rendered + '</option>';
                    }));
                }

                var title = $('input#bbcpt-edit-title'),
                    content = $('textarea#bbcpt-edit-content'),
                    liTag = this.targetSelect.closest('li');
                switch (mode) {
                    case 'add':
                        title.val('');
                        content.val('');
                        title.removeAttr('disabled');
                        content.removeAttr('disabled');
                        liTag.hide();
                        break;
                    case 'modify':
                        var model = collection.findWhere({id: parseInt(this.targetSelect.val())});
                        if(model) {
                            title.val(model.attributes.title.rendered);
                            content.val(model.attributes.content.rendered);
                            title.removeAttr('disabled');
                            content.removeAttr('disabled');
                            liTag.show();
                        }
                        break;
                    case 'delete':
                        title.val('');
                        content.val('');
                        title.attr('disabled', 'disabled');
                        content.attr('disabled', 'disabled');
                        liTag.show();
                        break;
                }
            }
        });

        var init = function () {
            postCollection.fetch();
            pageCollection.fetch();
            cptCollection.fetch();
            controlView.render();
        };

        var thePostView = new PostView();
        var thePageView = new PageView();
        var theCptView = new CptView();
        var controlView = new ControlView();

        return {
            init: init,
            postView: thePostView,
            pageView: thePageView,
            cptView: theCptView,
            controlView: controlView
        };
    };

    $(document).ready(function () {
        wp.api.loadPromise.done(function () {
            var admin = adminMenu();
            admin.init();
            console.log(wpApiSettings);
            sessionStorage.removeItem('wp-api-schema-model' + wpApiSettings.apiRoot + wpApiSettings.versionString);
        });
    });
})(jQuery);
