<template>
  <div class="about">
    <h1>About</h1>
    <h2>Problems encountered during the exercise</h2>
    <p>The difficulties that I encountered during this exercise were quickly resolved.<br>
      I first had a little trouble with cors policies (Cross-Origin Resource Sharing). Indeed the requests to the Marvel API did not work from the local server. So I created a small proxy in order to query the Marvel API from a server that I could add to the authorized referrers of Marvel. <br>
    I understood later that I could add an asterisk to allow any referrer. But it is still better to limit the authorized addresses.</p>
    <p>
      I also had a bit of trouble finding a way to hash in javascript like the php hash function does. I finally found on <i>stack overflow</i> the use of a crypto library that allowed this.</p>

      <h2>To go further</h2>
      <ul>
        <li>Add a css framework for a better look</li>
        <li>Improve the store and simplify overall code</li>
        <li>Add a favorite feature</li>
        <li>Add a card game deck creation functionality</li>
      </ul>
    </div>
  </template>
  <style scoped>
  .about{
    font-size: 1.6rem;
    display: flex;
    flex-direction: column;
    width: -webkit-fit-content;
    width: -moz-fit-content;
    width: fit-content;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    padding: 1em 2em;
  }
  p{line-height: 1.2;}
  h2{text-align: center;}
  </style>
