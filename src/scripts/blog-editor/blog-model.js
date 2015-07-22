'use strict';

export default class BlogModel {

  constructor (options) {
    if (typeof options.postId !== 'undefined' ||
      options.postId !== null) {
      this._postId = options.postId;
    }
  }

  onUpdateRequiringUIUpdate () {
    if (!window.GauntFace.PubSub) {
      return;
    }

    window.GauntFace.PubSub.panelUpdateRequired.dispatch();
  }

  onIframeUpdateRequired () {
    if (!window.GauntFace.PubSub) {
      return;
    }

    window.GauntFace.PubSub.iframeUpdateRequired.dispatch();
  }

  get postId () {
    return this._postId;
  }

  set postId (postId) {
    if (this._postId) {
      console.warn('Cannot set postId, already defined');
      return;
    }

    this._postId = postId;
    window.GauntFace.PubSub.postIDUpdated.dispatch(postId);
  }

  get title () {
    return this._title;
  }

  set title (title) {
    this._title = title;

    this.onIframeUpdateRequired();
  }

  get excerpt () {
    return this._excerpt;
  }

  set excerpt (excerpt) {
    this._excerpt = excerpt;

    this.onIframeUpdateRequired();
  }

  get markdown () {
    return this._markdown;
  }

  set markdown(markdown) {
    this._markdown = markdown;

    this.onIframeUpdateRequired();
  }

  get greyScaleImg () {
    return this._greyScaleImg;
  }

  set greyScaleImg (imgUrl) {
    this._greyScaleImg = imgUrl;

    this.onUpdateRequiringUIUpdate();
  }

  get mainImg() {
    return this._mainImg;
  }

  set mainImg (imgUrl) {
    this._mainImg = imgUrl;

    this.onUpdateRequiringUIUpdate();
  }

  getJSONData () {
    var apiData = [];
    apiData.push({key: 'postId', value: this.postId});
    apiData.push({key: 'title', value: this.title});
    apiData.push({key: 'excerpt', value: this.excerpt});
    apiData.push({key: 'markdown', value: this.markdown});
    apiData.push({key: 'greyScaleImg', value: this.greyScaleImg});
    apiData.push({key: 'mainImg', value: this.mainImg});

    var responseObj = {};
    for (var i = 0; i < apiData.length; i++) {
      var key = apiData[i].key;
      var value = apiData[i].value;
      if (typeof value === 'undefined' || value === null) {
        continue;
      }

      responseObj[key] = value;
    }

    return responseObj;
  }
}