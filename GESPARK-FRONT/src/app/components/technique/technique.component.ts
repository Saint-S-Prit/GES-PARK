import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { TechniqueService } from './technique.service';

@Component({
  selector: 'app-technique',
  templateUrl: './technique.component.html',
  styleUrls: ['./technique.component.css']
})
export class TechniqueComponent implements OnInit {

  constructor(
    private techniqueService: TechniqueService,
    private sanitizer: DomSanitizer,) { }

    techniques: any;
    invoice: any;

  ngOnInit(): void {
    this.getTechnique();
  }

  getTechnique()
  {
    this.techniqueService.getTechnique().subscribe(
      (techniques) => {
          this.techniques = techniques;
          for (let index of this.techniques) {
              if (index.invoice) {
                let objectURL = 'data:image/jpeg;base64,' + index.invoice;
                this.invoice = this.sanitizer.bypassSecurityTrustUrl(objectURL);
                index.invoice = this.invoice;
              }
            }
      }
    )
  }

}
