(function ($) {

    var adminMenu = function () {

        // These are discouraged.
        // var CptModel = wp.api.models.Post.extend({
        //     urlRoot: wpApiSettings.root + wpApiSettings.versionString + 'bb-cpt'
        // });
        // var CptCollection = wp.api.collections.Posts.extend({
        //     url: wpApiSettings.root + wpApiSettings.versionString + 'bb-cpt',
        //     model: CptModel
        // });

        var postCollection = new wp.api.collections.Posts();

        var pageCollection = new wp.api.collections.Pages();

        // 중요: rest_base 가 'bb-cpt'. 이를 camelCase 형태로 만든 'BbCpt' 가 이미 만들어져 있다.
        var cptCollection = new wp.api.collections.BbCpt();

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
            el: 'div#bb-cpt-control',

            events: {
                'change select#bb-cpt-post-type': 'render',
                'change select#bb-cpt-edit-target': 'render',
                'click input[name="bb-cpt-edit-mode"]': 'render',
                'click button#bb-cpt-edit-button': 'buttonSubmitClicked'
            },

            targetSelect: $('select#bb-cpt-edit-target'),

            buttonSubmitClicked: function () {
                var collection,
                    postType = $('#bb-cpt-post-type').val(),
                    mode = $('input[name="bb-cpt-edit-mode"]:checked', this.$el).data('mode'),
                    editTarget = parseInt($('select#bb-cpt-edit-target').val()),
                    model,
                    obj = {
                        title: $('input#bb-cpt-edit-title').val(),
                        author: $('select#bb-cpt-edit-author').val(),
                        content: $('textarea#bb-cpt-edit-content').val(),
                        type: postType,
                        status: 'publish'
                    },
                    defer;

                switch (postType) {
                    case 'post':
                        collection = postCollection;
                        break;
                    case 'page':
                        collection = pageCollection;
                        break;
                    case 'bb-cpt':
                        collection = cptCollection;
                        obj.tel = $('input#bb-cpt-meta-tel').val();
                        break;
                }

                switch (mode) {
                    case 'add':
                        defer = collection.create(obj);
                        break;

                    case 'modify':
                        model = collection.findWhere({'id': editTarget});
                        if (model) {
                            defer = model.save(obj);
                        }
                        break;

                    case 'delete':
                        model = collection.findWhere({id: editTarget});
                        if (model) {
                            var removed = collection.remove(model);
                            defer = removed.destroy();
                        }
                        break;
                }

                if (defer) {
                    defer.then(this.render);
                }
            },

            render: function (e) {
                var collection,
                    editType = $('#bb-cpt-post-type').val(),
                    mode = $('input[name="bb-cpt-edit-mode"]:checked', this.$el).data('mode'),
                    tel = $('input#bb-cpt-meta-tel');

                switch (editType) {
                    case 'post':
                        collection = postCollection;
                        tel.closest('li').hide();
                        break;
                    case 'page':
                        collection = pageCollection;
                        tel.closest('li').hide();
                        break;
                    case 'bb-cpt':
                        collection = cptCollection;
                        tel.closest('li').show();
                        break;
                }

                if ((e && e.target.id !== this.targetSelect.attr('id')) || !e) {
                    this.targetSelect.html(_.map(collection.models, function (model) {
                        return '<option value="' + model.attributes.id + '">' + model.attributes.title.rendered + '</option>';
                    }));
                }

                var title = $('input#bb-cpt-edit-title'),
                    content = $('textarea#bb-cpt-edit-content'),
                    liTag = this.targetSelect.closest('li');
                switch (mode) {
                    case 'add':
                        title.val('');
                        content.val('');
                        tel.val('');
                        title.removeAttr('disabled');
                        content.removeAttr('disabled');
                        tel.removeAttr('disabled');
                        liTag.hide();
                        break;
                    case 'modify':
                        var model = collection.findWhere({id: parseInt(this.targetSelect.val())});
                        if (model) {
                            title.val(model.attributes.title.rendered);
                            content.val(model.attributes.content.rendered);
                            tel.val(model.attributes.tel);
                            title.removeAttr('disabled');
                            content.removeAttr('disabled');
                            tel.removeAttr('disabled');
                            liTag.show();
                        }
                        break;
                    case 'delete':
                        title.val('');
                        content.val('');
                        tel.val('');
                        title.attr('disabled', 'disabled');
                        content.attr('disabled', 'disabled');
                        tel.attr('disabled', 'disabled');
                        liTag.show();
                        break;
                }
            }
        });

        var init = function () {
            postCollection.fetch();
            pageCollection.fetch();
            cptCollection.fetch().then(function () {
                controlView.render();
            });
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
        });
    });
})(jQuery);
