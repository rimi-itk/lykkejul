/* global winwheel_settings */

import { gsap } from 'gsap'
import { CSSPlugin } from 'gsap/CSSPlugin.js'

require('../css/play.scss')

const $ = require('jquery')
require('bootstrap')
const Winwheel = require('winwheel')
gsap.registerPlugin(CSSPlugin)

// @TODO Do this the right way!
window.TweenMax = require('gsap').TweenMax

const getRandomInt = (min, max) => {
  min = Math.ceil(min)
  max = Math.floor(max)

  return Math.floor(Math.random() * (max - min)) + min // The maximum is exclusive and the minimum is inclusive
}

$(() => {
  const $select = $('#play_player')
  const $options = $select.find('option')
  const random = getRandomInt(0, $options.length)
  $options.eq(random).prop('selected', true)

  window.alertPrize = () => {
    const segment = wheel.getIndicatedSegment()

    console.log(segment)
    $('#winner-name').html(segment.text)

    $select.val(segment.id)

    $('#winModal').modal({})
  }

  const winWheelArguments = Object.assign(winwheel_settings.constructor_arguments || {}, {
    animation: // Note animation properties passed in constructor parameters.
        {
          type: 'spinToStop', // Type of animation.
          duration: 1, // 5,             // How long the animation is to take in seconds.
          spins: 8, // The number of complete 360 degree rotations the wheel is to do.
          callbackFinished: 'alertPrize()'
        },
    canvasId: 'wheel',
    pointerAngle: 90
    // 'pointerGuide': {'display': true, 'strokeStyle': 'red', 'lineWidth': 3},
  })

  // Make wheel canvas fill wrapper.
  const $wheel = $('#wheel')
  const $wrapper = $wheel.parent()
  $wheel.attr('width', $wrapper.width() + 'px').attr('height', $wrapper.height() + 'px')

  const wheel = new Winwheel(winWheelArguments)

  // Apply colors to segments.
  const colors = winwheel_settings.colors
  wheel.segments.forEach((segment, index) => {
    if (segment) {
      segment.fillStyle = colors[index % colors.length]
    }
  })
  wheel.draw()

  $('#spin-the-wheel').on('click', () => {
    wheel.stopAnimation(false)
    wheel.rotationAngle = 0
    wheel.startAnimation()
  })
})
