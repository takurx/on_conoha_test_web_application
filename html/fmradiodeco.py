#!/usr/bin/env python2
# -*- coding: utf-8 -*-
##################################################
# GNU Radio Python Flow Graph
# Title: Top Block
# Generated: Tue Sep 12 22:16:41 2017
##################################################

import sys
from gnuradio import analog
from gnuradio import blocks
from gnuradio import eng_notation
from gnuradio import filter
from gnuradio import gr
from gnuradio.eng_option import eng_option
from gnuradio.filter import firdes
from optparse import OptionParser


class top_block(gr.top_block):

    def __init__(self):
        gr.top_block.__init__(self, "Top Block")

        ##################################################
        # Variables
        ##################################################
        self.samp_rate = samp_rate = 2400000
        self.resamp_rate = resamp_rate = 200000
        self.audio_rate = audio_rate = 48000
        self.audio_interp = audio_interp = 4
        self.audio_gain_0 = audio_gain_0 = 5

        ##################################################
        # Blocks
        ##################################################
        self.rational_resampler_xxx_0 = filter.rational_resampler_ccc(
                interpolation=resamp_rate,
                decimation=samp_rate,
                taps=None,
                fractional_bw=None,
        )
        self.low_pass_filter_0 = filter.fir_filter_ccf(1, firdes.low_pass(
        	1, samp_rate, resamp_rate/2, resamp_rate/20, firdes.WIN_HAMMING, 6.76))
        self.blocks_wavfile_source_0 = blocks.wavfile_source(sys.argv[1], False)
        self.blocks_wavfile_sink_0 = blocks.wavfile_sink(sys.argv[2], 1, audio_rate, 8)
        self.blocks_multiply_const_vxx_0 = blocks.multiply_const_vff((audio_gain_0, ))
        self.blocks_float_to_complex_0 = blocks.float_to_complex(1)
        self.analog_wfm_rcv_0 = analog.wfm_rcv(
        	quad_rate=audio_rate * audio_interp,
        	audio_decimation=audio_interp,
        )
        self.analog_fm_deemph_0 = analog.fm_deemph(fs=audio_rate, tau=50e-6)

        ##################################################
        # Connections
        ##################################################
        self.connect((self.analog_fm_deemph_0, 0), (self.blocks_multiply_const_vxx_0, 0))    
        self.connect((self.analog_wfm_rcv_0, 0), (self.analog_fm_deemph_0, 0))    
        self.connect((self.blocks_float_to_complex_0, 0), (self.low_pass_filter_0, 0))    
        self.connect((self.blocks_multiply_const_vxx_0, 0), (self.blocks_wavfile_sink_0, 0))    
        self.connect((self.blocks_wavfile_source_0, 0), (self.blocks_float_to_complex_0, 0))    
        self.connect((self.blocks_wavfile_source_0, 1), (self.blocks_float_to_complex_0, 1))    
        self.connect((self.low_pass_filter_0, 0), (self.rational_resampler_xxx_0, 0))    
        self.connect((self.rational_resampler_xxx_0, 0), (self.analog_wfm_rcv_0, 0))    

    def get_samp_rate(self):
        return self.samp_rate

    def set_samp_rate(self, samp_rate):
        self.samp_rate = samp_rate
        self.low_pass_filter_0.set_taps(firdes.low_pass(1, self.samp_rate, self.resamp_rate/2, self.resamp_rate/20, firdes.WIN_HAMMING, 6.76))

    def get_resamp_rate(self):
        return self.resamp_rate

    def set_resamp_rate(self, resamp_rate):
        self.resamp_rate = resamp_rate
        self.low_pass_filter_0.set_taps(firdes.low_pass(1, self.samp_rate, self.resamp_rate/2, self.resamp_rate/20, firdes.WIN_HAMMING, 6.76))

    def get_audio_rate(self):
        return self.audio_rate

    def set_audio_rate(self, audio_rate):
        self.audio_rate = audio_rate

    def get_audio_interp(self):
        return self.audio_interp

    def set_audio_interp(self, audio_interp):
        self.audio_interp = audio_interp

    def get_audio_gain_0(self):
        return self.audio_gain_0

    def set_audio_gain_0(self, audio_gain_0):
        self.audio_gain_0 = audio_gain_0
        self.blocks_multiply_const_vxx_0.set_k((self.audio_gain_0, ))


def main(top_block_cls=top_block, options=None):

    tb = top_block_cls()
    tb.start()
    tb.wait()


if __name__ == '__main__':
    main()
